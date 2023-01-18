// message_send.php에서 입력된 값을 체크하기 위해 사용
function checkSendMessage() {
    console.log('작동');
    let error = document.querySelector('.error');

    if (!document.message_send_form.message_receive_id.value) {
        error.classList.add('active');
        error.textContent = "받는 사람을 입력하세요"
        document.message_send_form.message_receive_id.focus();
        return;
    } 
    
    if (!document.message_send_form.message_title.value) {
        error.classList.add('active');
        error.textContent = "제목을 입력하세요"
        document.message_send_form.message_title.focus();
        return;

    }

    if (!document.message_send_form.message_content.value) {
        error.classList.add('active');
        error.textContent = "내용을 입력하세요"
        document.message_send_form.message_content.focus();
        return;
    }


    document.message_send_form.submit();
}

// message_send.php에서 전체 보내기를 체크하면 받는 사람란이 비활성화 되게 하기 위해 사용
function sendAll() {
    console.log('작동');
    let checkbox = document.querySelector('#checkbox');
    let receiver = document.querySelector('.receiver');

    if (checkbox.checked){
        console.log('체크');
        receiver.classList.add('active');
        document.message_send_form.message_receive_id.value = "전체 보내기";
    } else {
        console.log('해제');
        receiver.classList.remove('active');
        document.message_send_form.message_receive_id.value = "";
    }
}