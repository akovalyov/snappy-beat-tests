FROM sandrokeil/php:5.6-cli

ENV LC_ALL es_ES.utf8

RUN apt-get update && \
    apt-get -y install fontconfig \
      libfontconfig1 \
      libpng12-0 \
      libjpeg62-turbo \
      libx11-6 \
      libxext6 \
      libxrender1 \
      xfonts-base \
      xfonts-75dpi && \
    curl -sSL http://download.gna.org/wkhtmltopdf/0.12/0.12.2.1/wkhtmltox-0.12.2.1_linux-jessie-amd64.deb -o wk.deb && \
    dpkg -i wk.deb

RUN mkdir -p /app

RUN apt-get install locales && localedef -i es_ES -f UTF-8 es_ES.UTF-8
WORKDIR /app
