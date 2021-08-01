#!/bin/bash
{ date +"[%Y-%m-%dT%H:%M:%S%z]:"; /usr/bin/curl -s http://api.renegociacao/import/occurrences; echo "";}  >> /var/www/app-renegociacao/logs/importacao-ocorrencias.log 2>&1
exit
