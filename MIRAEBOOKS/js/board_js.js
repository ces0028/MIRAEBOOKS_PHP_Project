// board_post.php에서 게시물을 등록하려고 할 때 내용을 체크할 때 사용
function checkInsertInput() {
    let error = document.querySelector('.error');
   
    if (!document.board_write_form.board_title.value) {
        error.classList.add('active');
        error.textContent = "제목을 입력하세요"
        document.board_write_form.board_title.focus();
        return;
    }

    if (!document.board_write_form.board_content.value) {
        error.classList.add('active');
        error.textContent = "내용을 입력하세요"
        document.board_write_form.board_content.focus();
        return;
    }

    document.board_write_form.submit();
}

// board_update.php에서 게시물을 수정한 내용을 체크할 때 사용
function checkUpdatePost() {
    let error = document.querySelector('.error');
   
    if (!document.board_update_form.board_title.value) {
        error.classList.add('active');
        error.textContent = "제목을 입력하세요"
        document.board_update_form.board_title.focus();
        return;
    }

    if (!document.board_update_form.board_content.value) {
        error.classList.add('active');
        error.textContent = "내용을 입력하세요"
        document.board_update_form.board_content.focus();
        return;
    }

    document.board_update_form.submit();
}

// board_qna.php에서 게시물 제목을 누르면 내용이, 내용을 누르면 답변이 활성화시키기 위해서 사용
function callJs(){
    let question_titles = document.querySelectorAll('.question_title');
    let question_contents = document.querySelectorAll('.question_content');
    let answers = document.querySelectorAll('.answer');

    for (let i = 0; i < question_titles.length; i++) {
        qnaEvent(i)
    }

    function qnaEvent(selectNumber) {
        console.log(selectNumber);
        question_titles[selectNumber].addEventListener('click', ()=>{
            if (question_contents[selectNumber].classList.contains('active')) {
                question_contents.forEach((question_content) => {
                    question_content.classList.remove('active');
                    answers.forEach((answer) => {
                        answer.classList.remove('active');
                    })
                })
            } else {
                question_contents.forEach((question_content) => {
                    question_content.classList.remove('active');
                    answers.forEach((answer) => {
                        answer.classList.remove('active');
                    })
                })
                question_contents[selectNumber].classList.add('active');
            }
        })

        question_contents[selectNumber].addEventListener('click', ()=>{
            if (answers[selectNumber].classList.contains('active')) {
                answers.forEach((answer) => {
                    answer.classList.remove('active');
                })
            } else {
                answers.forEach((answer) => {
                    answer.classList.remove('active');
                })
                answers[selectNumber].classList.add('active');
            }
        })
    }
}

// board_qna_answer에서 입력된 답변을 체크할 때 사용
function checkInsertQnaAnswer() {
    let error = document.querySelector('.error');
   
    if (!document.answer_write_form.qna_answer.value) {
        error.classList.add('active');
        error.textContent = "답변을 입력하세요"
        document.answer_write_form.qna_answer.focus();
        return;
    }

    document.answer_write_form.submit();
}

// board_application에서 입력된 신청서 내용을 체크할 때 사용
function checkApplication() {
    let error = document.querySelector('.error');
   
    if (!document.application.request_lang.value) {
        error.classList.add('active');
        error.textContent = "언어를 선택하세요"
        document.application.request_lang.focus();
        return;
    }
   
    if (!document.application.request_title.value) {
        error.classList.add('active');
        error.textContent = "제목을 입력하세요"
        document.application.request_title.focus();
        return;
    }
   
    if (!document.application.request_author.value) {
        error.classList.add('active');
        error.textContent = "저자를 선택하세요"
        document.application.request_author.focus();
        return;
    }

    document.application.submit();
}