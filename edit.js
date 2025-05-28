const deleteAccount = document.getElementById("remove-btn")
const alertContainer = document.getElementById("alertContainer")
const noBtn = document.getElementById("close-btn")
const yesBtn = document.getElementById("confirmed-btn")
console.log(deleteAccount)
console.log(alertContainer)
console.log(noBtn)
console.log(yesBtn)
const fileInput = document.getElementById('img-profile')
const profileImage = document.getElementById('img-show')
fileInput.addEventListener("change", function () {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      profileImage.src = e.target.result;
    };
    reader.readAsDataURL(file);
  }
});

deleteAccount.addEventListener("click", function(){
    alertContainer.classList.remove("d-none")
    alertContainer.classList.add("d-flex")
})

noBtn.addEventListener("click", function(){
    alertContainer.classList.remove("d-flex")
    alertContainer.classList.add("d-none")
})

yesBtn.addEventListener("click", function(){
    location.href = 'logout.php?user_id=' 
})