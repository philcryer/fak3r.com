hugo server --watch --baseURL http://$(ping `hostname`|head -n1|awk -F'(' '{print $2}'|cut -d')' -f1) --bind 0.0.0.0
