# Docker / PHP 7.4 console / composer / phpunit 

Docker project for console php 7.4 projects with composer and phpunit.

#Task 
[Click here](https://gist.github.com/mariusbalcytis/e73370f4d2bda302c7bd867dfeef9751)

## Prerequisites

Install Docker and optionally Make utility.

Commands from Makefile could be executed manually in case Make utility is not installed.

## Build container.

    Make build
    
## Run docker containers

    Make up
    
## Check docker containers

    Make check

## Copy dist configs

If dist files are not copied to actual destination, then
    
    Make copy-dist-configs
    
## Install the composer dependencies

    Make vendors-install
    
## Run unit tests

Runs container and executes unit tests.

    Make unit-tests
    
## Run functional tests

Runs container and executes unit tests.

    Make functional-tests

## Static analysis

Static analysis check

    Make static-analysis
    
## Run cs-fixer
    
    Make cs-fix
	    
## Results

Input data variants 
  with default api services

    docker exec -it php74-cli php app.php input.txt
   
   with auth Bin service

    docker exec -it php74-cli php app.php input.txt --apiBinUrl=https://lookup.binlist.net/ --authBinType=basic --authBinLogin=admin --authBinPassword=pass
    
   without any auth
    
    docker exec -it php74-cli php app.php input.txt --apiBinUrl=https://lookup.binlist.net/ --apiRatesUrl=https://api.exchangeratesapi.io/latest
    
  with auth in both service
   
    docker exec -it php74-cli php app.php input.txt --apiBinUrl=https://lookup.binlist.net/ --apiRatesUrl=https://api.exchangeratesapi.io/latest --authBinType=basic --authBinLogin=admin --authBinPassword=pass --authRatesType=basic --authRatesLogin=admin --authRatesPassword=pass
    