// nav_admin.php에서 BOARD를 클릭하면 세부 게시판 항목이 활성화되도록 함
document.addEventListener("DOMContentLoaded", () => {
    let boardGroup = document.querySelector('#board_group');
    let boards = document.querySelectorAll('#board');

    boardGroup.addEventListener("click", function() {
        if (boardGroup.classList.contains('active')) {
            boardGroup.classList.remove('active');
        } else {
            boardGroup.classList.add('active');
        }
        boards.forEach((board) => {
            if (board.classList.contains('active')) {
                board.classList.remove('active');
            } else {
                board.classList.add('active');
            }
        })
    })
});

/* admin_member.php, admin_message.php, admin_books.php, admin_board_free.php에서
제목줄의 체크박스를 클릭하면 하단의 모든 체크박스가 동시에 체크 및 해제 되도록 함*/
function checkALL() {
    let selectButton = document.querySelector('#select_button');
    let selection = document.querySelectorAll('#checkbox');

    for (let i = 0; i < selection.length; i++) {
        selection[i].checked = selectButton.checked;
        console.log(selectButton.checked);
        console.log(selection[i].checked);
    }
}

// ebook_add.php 에서 ebook 정보 입력 체크
function checkInsertEbook() {
    let error = document.querySelector('.error');
    let select = document.querySelector('#ebook_lang');

    if (select.options[select.selectedIndex].value === "none") {
        error.classList.add('active');
        error.textContent = "언어를 입력하세요"
        return;
    }
   
    if (!document.ebook_insert_form.ebook_title.value) {
        error.classList.add('active');
        error.textContent = "제목을 입력하세요"
        document.ebook_insert_form.ebook_title.focus();
        return;
    }
   
    if (!document.ebook_insert_form.ebook_author.value) {
        error.classList.add('active');
        error.textContent = "저자를 입력하세요"
        document.ebook_insert_form.ebook_author.focus();
        return;
    }
    
    if (!document.ebook_insert_form.ebook_bookcover.value) {
        error.classList.add('active');
        error.textContent = "표지를 첨부하세요"
        return;
    }
   
    if (!document.ebook_insert_form.ebook_content.value) {
        error.classList.add('active');
        error.textContent = "링크를 첨부하세요"
        return;
    }
   
    if (!document.ebook_insert_form.ebook_file.value) {
        error.classList.add('active');
        error.textContent = "파일을 첨부하세요"
        return;
    }

    document.ebook_insert_form.submit();
}