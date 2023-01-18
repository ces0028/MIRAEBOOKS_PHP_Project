// login.php에서 입력된 값을 체크하기 위해 사용
function check_login() {
    let error = document.querySelector('.error');
    if (!document.login_form.member_id.value) {
        error.classList.add('active');
        error.textContent = "아이디를 입력하세요"
        document.login_form.member_id.focus();
        return;
    }

    if (!document.login_form.member_password.value) {
        error.classList.add('active');
        error.textContent = "비밀번호를 입력하세요"
        document.login_form.member_password.focus();
        return;
    }

    document.login_form.submit();
}

// find_id.php에서 입력된 값을 체크하기 위해 사용
function checkFindId() {
    let error = document.querySelector('.error');
    if (!document.find_id_form.member_name.value) {
        error.classList.add('active');
        error.textContent = "이름을 입력하세요"
        document.find_id_form.member_name.focus();
        return;
    }

    if (!document.find_id_form.member_email.value) {
        error.classList.add('active');
        error.textContent = "이메일을 입력하세요"
        document.find_id_form.member_email.focus();
        return;
    }

    if (!document.find_id_form.member_tel.value) {
        error.classList.add('active');
        error.textContent = "전화번호를 입력하세요"
        document.find_id_form.member_tel.focus();
        return;
    }

    document.find_id_form.submit();
}

// find_id.php에서 아이디를 클릭하면 자동으로 클립보드에 복사되게 하기 위한 함수
function copy_id(member_id) {
    const text = member_id;
    const textarea = document.createElement('textarea');
    textarea.textContent = text;
    document.body.append(textarea);
    textarea.select();
    document.execCommand('copy');
    textarea.remove();
    alert('아이디가 복사되었습니다');
}

// find_pw.php에서 입력된 값을 체크하기 위해 사용
function checkFindPw() {
    let error = document.querySelector('.error');
    if (!document.find_pw_form.member_id.value) {
        error.classList.add('active');
        error.textContent = "아이디를 입력하세요"
        document.find_pw_form.member_id.focus();
        return;
    }

    if (!document.find_pw_form.member_name.value) {
        error.classList.add('active');
        error.textContent = "이름을 입력하세요"
        document.find_pw_form.member_name.focus();
        return;
    }

    if (!document.find_pw_form.member_email.value) {
        error.classList.add('active');
        error.textContent = "이메일을 입력하세요"
        document.find_pw_form.member_email.focus();
        return;
    }

    if (!document.find_pw_form.member_tel.value) {
        error.classList.add('active');
        error.textContent = "전화번호를 입력하세요"
        document.find_pw_form.member_tel.focus();
        return;
    }

    document.find_pw_form.submit();
}

function checkSetNewPw() {
    let error = document.querySelector('.error');
    let success = document.querySelector('.success');
    if (!document.set_new_pw_form.member_password.value) {
        success.classList.remove('active');
        error.classList.add('active');
        error.textContent = "비밀번호를 입력하세요"
        document.find_pw_form.member_password.focus();
        return;
    }

    if (!document.set_new_pw_form.member_password_confirm.value) {
        success.classList.remove('active');
        error.classList.add('active');
        error.textContent = "다시 한 번 비밀번호를 입력하세요"
        document.find_pw_form.member_password_confirm.focus();
        return;
    }

    if (document.set_new_pw_form.member_password.value != document.set_new_pw_form.member_password_confirm.value) {
        success.classList.remove('active');
        error.classList.add('active');
        error.innerHTML = "비밀번호가 일치하지 않습니다.<br>다시 입력해 주세요"
        document.register_form.member_password.focus();
        return;
    }
    document.set_new_pw_form.submit();
}