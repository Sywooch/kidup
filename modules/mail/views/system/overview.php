<html>
<head>
    <title>Mails overview</title>
    <style>
        body {
            font-family: Verdana;
        }
    </style>
</head>
<body>
<table>
{% for mail, vars in mails %}
    {{ mail }}
{% endfor %}
</table>
</body>
</html>