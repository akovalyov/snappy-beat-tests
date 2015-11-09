<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Knp\Snappy\Pdf;
use Knp\FriendlyContexts\Context\ApiContext;
use Behat\Gherkin\Node\TableNode;

class FeatureContext extends ApiContext implements Context, SnippetAcceptingContext
{
    private $tmpDir;
    private $tmpHtmlFile;
    private $tmpPdfFile;
    private $renderer;

    public function __construct()
    {
        $this->tmpDir = sys_get_temp_dir();
        $this->tmpHtmlFile = tempnam($this->tmpDir, 'html');
        $this->renderer = new Pdf('/usr/local/bin/wkhtmltopdf');
    }

    public function __destruct()
    {
        $this->cleanup();
    }

    /**
     * @Given I have HTML with the following:
     */
    public function iRenderHtmlWithTheFollowing(PyStringNode $node)
    {
        $handle = fopen($this->tmpHtmlFile, 'w');
        fwrite($handle, $node->__toString());
        fclose($handle);
    }

    /**
     * @Given I render pdf file
     * @Given I render pdf file with the following:
     */
    public function iRenderPdfFile(TableNode $node = null)
    {
        $this->tmpPdfFile = $this->tmpDir.'/test.pdf';

        $this->renderer->setOptions($node instanceof TableNode ? $node->getRowsHash() : []);
        $this->renderer->generateFromHtml(file_get_contents($this->tmpHtmlFile), $this->tmpPdfFile);
    }

    /**
     * @Then I should see :string in pdf file
     */
    public function iShouldSeeInPdfFile($string)
    {
        $result = shell_exec(sprintf('curl -s -X PUT --data-binary @%s http://tika:9998/tika --header "Content-type: application/pdf"', realpath($this->tmpPdfFile)));
        if (false === strstr($result, $string)) {
            throw new \InvalidArgumentException(sprintf('String %s was not found in pdf file with contents %s', $string, trim($result)));
        }
    }

    /**
     * @Then I shouldn't see :string in pdf file
     */
    public function iShouldNotSeeInPdfFile($string)
    {
        try {
            $this->iShouldSeeInPdfFile($string);
        } catch (\InvalidArgumentException $e) {
            return;
        }
        throw new \InvalidArgumentException(sprintf('%s was not expected to be met in PDF', $string));
    }

    /**
     * @AfterScenario
     */
    public function cleanup()
    {
        if (file_exists($this->tmpHtmlFile)) {
            unlink($this->tmpHtmlFile);
        }
        if (file_exists($this->tmpPdfFile)) {
            unlink($this->tmpPdfFile);
        }
    }
}
