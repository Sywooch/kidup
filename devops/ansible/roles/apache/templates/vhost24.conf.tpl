# Default Apache virtualhost template

<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot {{ doc_root }}/web
{% set servernames = servername.split() %}
{% for servername in servernames %}
{% if loop.first %}
    ServerName {{ servername }}
{% else %}
    ServerAlias {{ servername }}
{% endif %}
{% endfor %}

    <Directory {{ doc_root }}/web>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
