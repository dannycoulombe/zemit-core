#!/bin/bash
rm -rf ./src/Models/Abstracts/Abstract*.php && ./vendor/bin/phalcon all-models --config=./src/Config/Migration.php --annotate --get-set --camelize --mapcolumn --abstract --doc --directory=./ --output=./src/Models/Abstracts --relations --fk --force --namespace=Zemit\\Models\\Abstracts --extends=\\Zemit\\Models\\AbstractModel "$@" && find ./src/Models/Abstracts/ -type f -exec sed -i -e '/$this->setSchema/i \\t\tparent::initialize();' {} \; && find ./src/Models/Abstracts/ -type f -exec sed -i -e 's/ $this->setSchema/ \/\/ $this->setSchema/g' {} \;
