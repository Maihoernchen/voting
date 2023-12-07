document.addEventListener('DOMContentLoaded', main);

function main () {
    var name = document.createElement('input');
    name.id = 'name';
    name.type = 'text';
    name.placeholder = 'Name der Umfrage';
    document.body.appendChild(name);
    var descr = document.createElement('input');
    descr.id = 'descr';
    descr.type = 'text';
    descr.placeholder = 'Beschreibung der Umfrage';
    document.body.appendChild(descr);
    var expirationDate = document.createElement('input');
    expirationDate.id = 'expirationDate';
    expirationDate.type = 'text';
    expirationDate.placeholder = 'YYYY-MM-DD HH:MI:SS';
    document.body.appendChild(expirationDate);
    var multiplePossible = document.createElement('input');
    multiplePossible.id = 'multiplePossible';
    multiplePossible.type = 'checkbox';
    multiplePossible.value = 'true';
    document.body.appendChild(multiplePossible);
    document.body.innerHTML += '<button id="addOption">Add Option</button>';
    document.body.innerHTML += '<button id="addSlider">Add Slider</button>';
    document.body.innerHTML += ' <form action="survey.php" method="post" onsubmit="return validateForm()" name="survey"><input type="submit" name="create" value="Create"></form>';
    document.getElementById('addOption').addEventListener('click', addOption);
    document.getElementById('addSlider').addEventListener('click', addSlider);
    renderOptions();
}

function addOption () {
    if (document.getElementById('option')) {
        alert ("Already working on an Option!");
    } else {
        var option = document.createElement('div');
        option.id = 'option';
        document.body.appendChild(option);
        var meaning = document.createElement('input');
        meaning.id = 'meaning';
        meaning.type = 'text';
        meaning.value = 'Put meaning of Option Here';
        document.getElementById('option').appendChild(meaning);
        var optionSubmit = document.createElement('button');
        optionSubmit.id = 'optionSubmit';
        optionSubmit.innerHTML = 'Submit Option';
        document.getElementById('option').appendChild(optionSubmit);
        document.getElementById('optionSubmit').addEventListener('click', optionToCookie);
    }
    renderOptions();
}

function optionToCookie() {
    const cookie = document.cookie;
	 
    // Split the cookie string into an array of key-value pairs
    const cookiePairs = cookie.split(";");
    
    // Initialize the highest number variable
    let highestNumber = 0;

    // Iterate through each key-value pair in the cookie
    for (let i = 0; i < cookiePairs.length; i++) {
        // Split the key-value pair into key and value
        let [key, value] = cookiePairs[i].trim().split("=");
        // Parse the value as a number
        if (!isNaN(key)) {
            // Check if the parsed value is a number and greater than the current highest number
            key = Number(key);
            if (key > highestNumber) {
                highestNumber = key;
            }
        }
    }
    currentNumber = Number(highestNumber)+1;
    document.cookie = currentNumber+'='+document.getElementById('meaning').value;
    highestNumber = 0;
    document.getElementById('option').remove();
    renderOptions();
}

function confirmOptions () {
    const cookie = document.cookie;
	 
    // Split the cookie string into an array of key-value pairs
    const cookiePairs = cookie.split(";");
    
    // Initialize the highest number variable
    
    var options = {};

    // Iterate through each key-value pair in the cookie
    for (let i = 0; i < cookiePairs.length; i++) {
        // Split the key-value pair into key and value
        let [key, value] = cookiePairs[i].trim().split("=");
        // Parse the value as a number
        if (!isNaN(key)) {
            // Check if the parsed value is a number and greater than the current highest number
            options[key] = value;
            document.cookie = key+'=; expires=Thu, 01 Jan 1970 00:00:00 UTC';
        }
    }
    return options;
}

function validateForm () {
    let name = document.getElementById('name').value;
    let descr = document.getElementById('descr').value;
    let multiplePossible = document.getElementById('multiplePossible').checked;
    let expirationDate = document.getElementById('expirationDate').value;
    let options = confirmOptions();
    if (name == "") {
        alert("Name must be filled out");
        return false;
    } else if (descr == '') {
        alert("Description must be filled out");
        return false;
    } else if (Object.keys(options).length==0) {
        alert("Option must be provided");
        return false;
    } else {
        options['name'] = name;
        options['descr'] = descr;
        options['multiplePossible'] = multiplePossible;
        options['expirationDate'] = expirationDate;
        document.cookie = 'survey='+JSON.stringify(options);
        return true;
    }
}

function renderOptions() {
    
    const cookie = document.cookie;
	 
    // Split the cookie string into an array of key-value pairs
    const cookiePairs = cookie.split(";");

    var optionShowcase = document.createElement('div');
    optionShowcase.id = 'optionShowcase';
    document.body.appendChild(optionShowcase);

    // Iterate through each key-value pair in the cookie
    for (let i = 0; i < cookiePairs.length; i++) {
        // Split the key-value pair into key and value
        let [key, value] = cookiePairs[i].trim().split("=");
        // Parse the value as a number
        if (!isNaN(key)) {
            if (!document.getElementById('showedOption'+key)) {
                var option = document.createElement('div');
                option.id = 'showedOption'+key;
                option.innerHTML = key+' = '+value;
                document.getElementById('optionShowcase').appendChild(option);
            }
        }
    }
}