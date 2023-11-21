#!/bin/bash

vendor/bin/phinx rollback -e development -t 0
vendor/bin/phinx rollback -e test -t 0
