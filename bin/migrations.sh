#!/bin/bash

vendor/bin/phinx migrate -e development
vendor/bin/phinx migrate -e test
