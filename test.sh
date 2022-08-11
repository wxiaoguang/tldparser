#!/usr/bin/env bash

set -e 
DIR=$( cd -- "$( dirname -- "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )

pushd "$DIR/go" > /dev/null
echo "test go code"
go test ./tldparser
popd > /dev/null

pushd "$DIR/php" > /dev/null
echo "test php code"
php TldParserTest.php
popd > /dev/null

pushd "$DIR/python" > /dev/null
echo "test python code"
python ./tldparser/__init__.py
popd > /dev/null
