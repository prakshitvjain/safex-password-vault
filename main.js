/*
let categoryList = document.getElementById("categoryList");

function toggleDropdown(){
    categoryList.classList.toggle("show");
}

let selectedCategory = document.getElementById("selectedCategory");

function selectCategory(category){
    selectedCategory.innerHTML = category;
    categoryList.classList.toggle("show");
}
*/

let addItemContainer = document.getElementById("addItemContainer");
let overlay = document.getElementById("overlay");

function toggleAddItemContainer(){
    addItemContainer.classList.toggle("showAddItemContainer");
    overlay.classList.toggle("active");
}

let password = document.getElementById("password");
let togglePassword = document.getElementById("togglePassword");

function togglePass(){
    let type = password.getAttribute("type");
    type = (type == "password") ? "text" : "password";
    password.setAttribute("type",type);

    togglePassword.classList.toggle("bi-eye");
}

function copyText(element1){
    // Only works on HTTPS connection or localhost and not on local Servers
    const element = document.getElementById(element1);
    element.select();
    navigator.clipboard.writeText(element.value);
    alert("Copied");
}

function checkPasswordStrength() {
    let len = password.value.length;
    let passwordStrength = document.getElementById("passwordStrength");
    let strength = document.getElementById("strength");
    let complexity = false;
    const checkupper = /[A-Z]/.test(password.value);
    const checklower = /[a-z]/.test(password.value);
    const checknum = /[0-9]/.test(password.value);
    const checkspecial = /[\W]/.test(password.value);

    if(checkupper && checklower && checknum && checkspecial){
        complexity = true;
    }
    if (len == 0) {
        passwordStrength.textContent = "Password Strength:";
        strength.style.width = "0%";
        strength.className = "";
        return;
    }
    else if((len <= 6 && complexity) || (len <= 6 && !complexity) || (len > 6 && len <=10 && !complexity)){
        passwordStrength.textContent = "Password Strength: Weak";
        strength.className = "weak";
        strength.style.width = "20%";
    }
    else if((len > 6 && len <=10 && complexity) || (len > 10 && len <=15 && !complexity)){
        passwordStrength.textContent = "Password Strength: Fair";
        strength.className = "fair";
        strength.style.width = "40%";
    }
    else if((len > 10 && len <=15 && complexity) || (len > 15 && len <=20 && !complexity)){
        passwordStrength.textContent = "Password Strength: Medium";
        strength.className = "medium";
        strength.style.width = "60%";
    }
    else if((len > 15 && len <=20 && complexity) || (len > 20 && len <=30 && !complexity)){
        passwordStrength.textContent = "Password Strength: Strong";
        strength.className = "strong";
        strength.style.width = "80%";
    }
    else if(len > 20 && len <=30 && complexity){
        passwordStrength.textContent = "Password Strength: Very Strong";
        strength.className = "verystrong";
        strength.style.width = "100%";
    }
}

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
    for( let i = 0; i < 16 ; i++){
        resultSet+= randomChar();
    }
    
let generatedPasswordField = document.getElementById("generatedPassword");
generatedPasswordField.value = resultSet;
}

let name = document.getElementById("name");
let username = document.getElementById("username");
function validateForm(){
    let result = true;
    if (name.value.trim() == ""){
        nameError.innerHTML = "Enter a name";
        result = false;
    }
    else {
        nameError.innerHTML="";
        result = true;

        if (username.value.trim() == ""){
            usernameError.innerHTML = "Enter a username";
            result = false;
        }
        else {
            usernameError.innerHTML="";
            result = true;

            if (password.value.trim() == "") {
                passwordError.innerHTML = "Enter a password";
                result = false;
            }
            else {
                passwordError.innerHTML="";
                result = true;
            }
        }
    }
    return result;
}
//Local storage implementation
let items = [];
/*
document.addEventListener("DOMContentLoaded",() => {
    const storedItems = JSON.parse(localStorage.getItem('items'));
    if(storedItems){
        items = storedItems;
        updateItemsList();
    }
});
*/

document.addEventListener("DOMContentLoaded", async () => {
    try {
        const response = await fetch('get_items.php');
        const data = await response.json();
        if (data.error){
            
            return;
        }
        items = data.items;
        updateItemsList();
    } catch(error) {
        console.error("Fetch error", error);
    }
});

document.getElementById("submit").addEventListener("click", function(event){
    event.preventDefault();
    addItem();
});

let notes = document.getElementById("notes");
let editingIndex = null;
let editingId = null;

const addItem = async () => {
    if(name.value && username.value && password.value){
        const formData = new FormData();
        formData.append("name", name.value);
        formData.append("username", username.value);
        formData.append("password", password.value);
        formData.append("notes", notes.value);

        if(editingId !== null) {
            formData.append("id", editingId);
        }
        try {
            const response = await fetch('add_item.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            if(result.error){
                alert("Error:" + result.message);
            }
            else {
                alert(editingId !== null ? "Item updated successfully" : "Item added successfully");
                const refresh = await fetch('get_items.php');
                const data = await refresh.json();
                items = data.items;
                updateItemsList(); 
                toggleAddItemContainer();
                name.value = '';
                username.value = '';
                password.value = '';
                notes.value = '';
                editingId = null;
                editingIndex = null;
                document.getElementById("submit").textContent = "Save";
            }

        } catch(err) {
            console.error("Error adding item",err);
            alert("An error occured. Please try again.");
        }
    }
}

const updateItemsList = () => {
    let itemsList = document.getElementById("itemsList");
    itemsList.innerHTML = "";

    items.forEach((item,index) => {
        const listItem = document.createElement("li");
        listItem.innerHTML = `
        <div class="itemDiv" id="itemDiv">
            <p onclick="editItemContainer(${index})">${item.name}</p>
            <div class='action' id='action-${index}'>
                <span onclick='deleteItem(${index})'>Delete</span>
            </div>
            <i class="bi bi-three-dots-vertical" onclick="actions(${index})"></i>
        </div>
        `;
        itemsList.appendChild(listItem);
    })
};

/*
const saveItems = () => {
    localStorage.setItem('items',JSON.stringify(items));
};
*/

const deleteItem = async (index) => {
    if(confirm("Are you sure you want to delete this item?")){
        const item_id = items[index].id;
        console.log('item id:' + item_id); 
        try {
            const response = await fetch("delete_item.php",{
                method: 'POST',
                body: JSON.stringify({item_id})
            });
            const result = await response.json();
            if(result.error){
                alert("Error:" + result.message);
            }
            else{
            alert('successfully Deleted');
            const refresh = await fetch('get_items.php');
            const data = await refresh.json();
            items = data.items;
            updateItemsList();
            }
        }
        catch(error){
            console.error("Error:" , error);
        }
        
    }
}
 
function actions(index){
    let action = document.getElementById(`action-${index}`);
    action.classList.toggle("showaction");
}

function editItemContainer(index){
    addItemContainer.classList.toggle("showAddItemContainer");
    overlay.classList.toggle("active");
    name.value = items[index].name;
    username.value = items[index].username;
    password.value = items[index].password;
    notes.value = items[index].notes;
    editingId = items[index].id;
    editingIndex = index;
    document.getElementById("submit").textContent = "Update";
}
