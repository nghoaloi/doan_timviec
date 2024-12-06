//Lấy ra element của trang

const formRegister = document.getElementById("formRegister");
const userNameElement = document.getElementById("userName");
const emailElement = document.getElementById("email");
const passwordElement = document.getElementById("password");
const repasswordElement = document.getElementById("repassword");

//Element liên quan đến lỗi
const userNameError = document.getElementById("userNameError");
const emailError = document.getElementById("emailError");
const passwordError = document.getElementById("passwordError");
const repasswordError = document.getElementById("repasswordError");

/**
 * validate địa chỉ email
 * @param {*} email: Chuỗi email người dùng nhập vào
 * @returns: Dữ liệu nếu email đúng định dạng, undifined nếu email không đúng định dạng
 * Author: DTHuy(19/11/2024)
 */
function validateEmail(email) {
  return String(email)
    .toLowerCase()
    .match(
      /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    );
}
//Lắng nghe sự kiện submit đăng ký tài khoản
formRegister.addEventListener("submit", function (e) {
  //Ngăn chặn sự kiện load lại trang
  e.preventDefault();

  //Validate dữ liệu đầu vào
  if (!userNameElement.value) {
    userNameError.style.display = "block";
  } else {
    //Ẩn lỗi
    userNameError.style.display = "none";
  }

  if (!emailElement.value) {
    emailError.style.display = "block";
  } else {
    //Ẩn lỗi
    emailError.style.display = "none";
    //Kiểm tra định dang email
    if (!validateEmail(emailElement.value)) {
      emailError.style.display = "block";
      emailError.innerHTML = "Email không đúng định dạng";
    }
  }
  if (!passwordElement.value) {
    passwordError.style.display = "block";
  } else {
    //Ẩn lỗi
    passwordError.style.display = "none";
  }
  if (!repasswordElement.value) {
    repasswordError.style.display = "block";
  } else {
    //Ẩn lỗi
    repasswordError.style.display = "none";
  }

  //Kiểm tra mật khẩu với nhập lại mật khẩu
  if (passwordElement.value !== repasswordElement.value) {
    repasswordError.style.display = "block";
    repasswordError.innerHTML = "Mật khẩu không khớp";
  } else {
    repasswordError.style.display = "none";
  }

  //Gửi dữ liệu từ form lên localStorgate
  if (
    userNameElement.value &&
    emailElement.value &&
    passwordElement.value &&
    repasswordElement.value &&
    passwordElement.value == repasswordElement.value &&
    validateEmail(emailElement.value)
  ) {
    console.log("submit");
  }
});
