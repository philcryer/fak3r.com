{{ $paginator := .Paginate (where .Data.Pages "Type" "!=" "page") }}
	{{ range $paginator.Pages }}
	{{ .Content }}    
{{ end }}
{{ partial "header.html" . }}
