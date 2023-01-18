// register.php에서 입력된 값을 체크하기 위해 사용
function checkInsertMember() {
    let error = document.querySelector('.error');
    if (!document.register_form.member_id.value) {
        error.classList.add('active');
        error.textContent = "아이디를 입력하세요"
        document.register_form.member_id.focus();
        return;
    }

    if (!document.register_form.member_password.value) {
        error.classList.add('active');
        error.textContent = "비밀번호를 입력하세요"
        document.register_form.member_password.focus();
        return;
    }

    if (!document.register_form.member_password_confirm.value) {
        error.classList.add('active');
        error.textContent = "비밀번호를 한 번 더 입력하세요"
        document.register_form.member_password_confirm.focus();
        return;
    }

    if (!document.register_form.member_name.value) {
        error.classList.add('active');
        error.textContent = "이름을 입력하세요"
        document.register_form.member_name.focus();
        return;
    }

    if (!document.register_form.member_gender.value) {
        error.classList.add('active');
        error.textContent = "성별을 선택하세요"
        document.register_form.member_gender.focus();
        return;
    }

    if (!document.register_form.member_email.value) {
        error.classList.add('active');
        error.textContent = "이메일 주소를 입력하세요"   
        document.register_form.member_email.focus();
        return;
    }

    if (!document.register_form.member_tel.value) {
        error.classList.add('active');
        error.textContent = "전화번호를 입력하세요"   
        document.register_form.member_tel.focus();
        return;
    }

    if (document.register_form.member_password.value != document.register_form.member_password_confirm.value) {
        error.classList.add('active');
        error.textContent = "비밀번호가 일치하지 않습니다.\n다시 입력해 주세요"
        document.register_form.member_password.focus();
        return;
    }
    document.register_form.submit();
}

// register.php에서 중복확인 버튼을 클릭했을 때 새로운 창을 출력하기 위해 사용
function checkId() {
    let option = "left=700,top=300,width=350,height=150,scrollbars=no";
    window.open("register_check_id.php?id=" + document.register_form.member_id.value, "아이디 중복확인", option);
}