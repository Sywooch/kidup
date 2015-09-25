var json = require("./common.package.json");

for(var i = 0; i <= json.css.length; i++){
    i.replace("/vagrant/", '');
    require(''+i);
}

for(i = 0; i <= json.js.length; i++){
    i.replace("/vagrant/", '');
    require(''+i);
}