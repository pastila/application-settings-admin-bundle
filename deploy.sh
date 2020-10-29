#!/bin/bash

set -e

vendor/bin/dep prepare_workspace $1_host -vvv
vendor/bin/dep start_workspace $1_host -vvv
vendor/bin/dep deploy $1_workspace -vvv
vendor/bin/dep start_services $1_host -vvv
