function copyText(element1){
    // Only works on HTTPS connection or localhost and not on local Servers
    const element = document.getElementById(element1);
    element.select();
    navigator.clipboard.writeText(element.value);
    alert("Copied");
}

const lengthInput = document.querySelector("#lengthInput");
const selectedLength = document.querySelector("#selectedLength");
selectedLength.textContent = lengthInput.value;
lengthInput.addEventListener("input", () => {
    selectedLength.textContent = lengthInput.value;
});


function generateRandomPassword(){
    let randomChar = () => {
        const charSetArray = [
            [33,47],[48,57],[65,90],[91,96],[97,122]
        ];
        let charSet = charSetArray[Math.floor(Math.random() * 5)];
        let charCode = Math.floor(Math.random() * (charSet[1] - charSet[0] + 1)) + charSet[0];
        return String.fromCharCode(charCode);
    };  
    let resultSet = '';
    for( let i = 0; i < lengthInput.value ; i++){
        resultSet+= randomChar();
    }
    
let generatedPasswordField = document.getElementById("generatedPassword");
generatedPasswordField.value = resultSet;
}



