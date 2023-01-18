// mypage_edit.php에서 입력된 값을 체크하기 위해 사용
function checkUpdateInfo() {
    let error = document.querySelector('.error');
   
    if (!document.modify_form.member_password.value) {
        error.classList.add('active');
        error.textContent = "비밀번호를 입력하세요"
        document.modify_form.member_password.focus();
        return;
    }

    if (!document.modify_form.member_password_confirm.value) {
        error.classList.add('active');
        error.textContent = "비밀번호 확인란을 입력하세요"
        document.modify_form.member_password_confirm.focus();
        return;
    }

    if (!document.modify_form.member_name.value) {
        error.classList.add('active');
        error.textContent = "이름을 입력하세요"
        document.modify_form.member_name.focus();
        return;
    }

    if (!document.modify_form.member_gender.value) {
        error.classList.add('active');
        error.textContent = "성별을 선택하세요"
        document.modify_form.member_gender.focus();
        return;
    }

    if (!document.modify_form.member_email.value) {
        error.classList.add('active');
        error.textContent = "이메일 주소를 입력하세요"   
        document.modify_form.member_email.focus();
        return;
    }

    if (!document.modify_form.member_tel.value) {
        error.classList.add('active');
        error.textContent = "전화번호를 입력하세요"   
        document.modify_form.member_tel.focus();
        return;
    }

    if (document.modify_form.member_password.value != document.modify_form.member_password_confirm.value) {
        error.classList.add('active');
        error.textContent = "비밀번호가 일치하지 않습니다.\n다시 입력해 주세요"
        document.modify_form.member_password.focus();
        return;
    }
    document.modify_form.submit();
}

// mypage_withdrawal.php에서 주의사항 체크박스가 체크 됐는지 & 입력된 값을 체크하기 위해 사용
function checkWithdrawal() {
    let error = document.querySelector('.error');
    if (!document.withdrawal_form.withdrawal_check.checked) {
        error.classList.add('active');
        error.textContent = "주의사항을 읽으신 후 박스를 체크하세요"
        return;
    }
    
    if (!document.withdrawal_form.member_id.value) {
        error.classList.add('active');
        error.textContent = "아이디를 입력하세요"
        document.withdrawal_form.member_id.focus();
        return;
    }

    if (!document.withdrawal_form.member_password.value) {
        error.classList.add('active');
        error.textContent = "비밀번호를 입력하세요"
        document.withdrawal_form.member_password.focus();
        return;
    }

    document.withdrawal_form.submit();
}