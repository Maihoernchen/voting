document.addEventListener('DOMContentLoaded', main);


function main () {

    var getCookies = function(){
        var pairs = document.cookie.split(";");
        var cookies = {};
        for (var i=0; i<pairs.length; i++){
          var pair = pairs[i].split("=");
          cookies[(pair[0]+'').trim()] = unescape(pair.slice(1).join('='));
        }
        return cookies;
    }
    if (getCookies()['multiplePossible']==0) {
        var options = document.querySelectorAll("input[type=checkbox]");
        console.log(options);

        for (var i = 0; i < options.length; i++) {
            options[i].addEventListener('change', function() {
                if (this.checked) {
                    onCheck();
                    this.checked = true;
                } else {
                    onCheck();
                }});
        }
    }
}

function onCheck () {
    var options = document.querySelectorAll("input[type=checkbox]");
    for (var i = 0; i < options.length; i++) {
        options[i].checked = false
    }
}

function validateVote () {
    console.log(document.getElementById('name').innerHTML, document.getElementById('descr').innerHTML);
    return false
}