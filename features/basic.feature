Feature:
  As a developer
  In order to render basic PDF file from HTML template
  I should be able to do it via Snappy lib

  Scenario: I render usual HTML file
    Given I have HTML with the following:
    """
    <h2>Hello world</h2>
    """
    And I render pdf file
    Then I should see "Hello world" in pdf file

  Scenario: I render usual HTML file with accents without appropriate charset meta tag
    Given I have HTML with the following:
    """
    <h2>Héllo world</h2>
    """
    And I render pdf file
    Then I shouldn't see "Héllo world" in pdf file

  Scenario: I render usual HTML file with accents with appropriate charset meta tag
    Given I have HTML with the following:
    """
    <meta charset="UTF-8" />
    <h2>Héllo world</h2>
    """
    And I render pdf file
    Then I should see "Héllo world" in pdf file

  Scenario: I render usual HTML file with accents in footer
    Given I have HTML with the following:
    """
    <meta charset="UTF-8" />
    <h2>Héllo world</h2>
    """
    And I render pdf file with the following:
      | footer-right | Página [page] de [toPage] |
    Then I should see "Héllo world" in pdf file
    And I should see "Página 1 de 1" in pdf file
