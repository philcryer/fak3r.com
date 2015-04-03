export GOPATH=$HOME/go
#baseUrl=`ping `hostname``
#echo $baseUrl
#~/go/bin/hugo server --buildDrafts --watch=true --theme=redlounge --baseUrl=192.168.1.110
#~/go/bin/hugo server --buildDrafts --watch=true --theme=redlounge --baseUrl=$baseUrl
~/go/bin/hugo server --buildDrafts --watch=true --theme=redlounge --baseUrl=$1
