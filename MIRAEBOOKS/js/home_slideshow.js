function call_js() {
    // 상단의 슬라이드쇼를 작동시키기 위해 정의
    let slideshowSlideFirst = document.querySelector('.first_slideshow_slide');
    let slidesFirst = document.querySelectorAll('.first_slideshow_slide a');
    let prevFirst = document.querySelector('#first_prev');
    let nextFirst = document.querySelector('#first_next');
    let indicatorsFirst = document.querySelectorAll('.first_slideshow_indicator a')
    let slideFirstCount = slidesFirst.length;
    let currentFirstIndex = 0;
    let timerFirst = 0;
    
    // 하단의 슬라이드쇼를 작동시키기 위해 정의
    let slideshowSlideSecond = document.querySelector('.second_slideshow_slide');
    let slidesSecond = document.querySelectorAll('.second_slideshow_slide a');
    let prevSecond = document.querySelector('#second_prev');
    let nextSecond = document.querySelector('#second_next');
    let indicatorsSecond = document.querySelectorAll('.second_slideshow_indicator a')
    let slideSecondCount = slidesSecond.length;
    let currentSecondIndex = 0;
    let timerSecond = 0;

    /* FIRST SLIDESHOW */
    // 입력된 모든 이미지를 왼쪽으로 일렬로 정렬함
    for (let i = 0; i < slideFirstCount; i++) {
        let newPosition = i * 300 + 'px';
        slidesFirst[i].style.left = newPosition;
    }

    // 화면을 옆으로 300px만큼 옮김
    // 화면을 옮길 때, 인디케이터의 값도 하나씩 옆의 값이 활성화되도록 함
    function moveFirstCamera(index) {
        currentFirstIndex = index;
        let newFirstCameraPosition = currentFirstIndex * -300 + 'px';
        slideshowSlideFirst.style.left = newFirstCameraPosition;
        indicatorsFirst.forEach((obj)=>{
            obj.classList.remove('active');
        });
        indicatorsFirst[index].classList.add('active');
    }

    // 화면을 옮길 때 3초만큼 딜레이를 줘서 천천히 움직이기 함
    function setFirstTimeDelay() {
        timerFirst = setInterval(function(){
            newFirstIndex = (currentFirstIndex + 1) % slideFirstCount;
            moveFirstCamera(newFirstIndex)
        }, 3000)
    }

    setFirstTimeDelay();

    // 왼쪽 화살표를 클릭하면 카메라를 앞으로 한 칸 옮김
    prevFirst.addEventListener('click', (e)=>{
        e.preventDefault();
        let newIndex = currentFirstIndex - 1;
        if (newIndex < 0) {
            newIndex = slideFirstCount - 1 ;
        }
        moveFirstCamera(newIndex);
    });

    // 오른쪽 화살표를 클릭하면 카메라를 뒤로 한 칸 옮김
    nextFirst.addEventListener('click', (e)=>{
        e.preventDefault();
        let newIndex = currentFirstIndex + 1;
        if (newIndex > slideFirstCount - 1) {
            newIndex = 0;
        }
        moveFirstCamera(newIndex);
    });

    // 화살표를 누르기 위해서 마우스 커서를 갖다대면 슬라이드 쇼를 잠시 멈춤
    prevFirst.addEventListener('mouseenter', ()=>{
        clearInterval(timerFirst);
    })
    
    nextFirst.addEventListener('mouseenter', ()=>{
        clearInterval(timerFirst);
    })
    
    // 슬라이드쇼 내의 ebook을 클릭하기 위해서 마우스 커서를 갖다대면 슬라이드 쇼를 잠시 멈춤
    slideshowSlideFirst.addEventListener('mouseenter', ()=>{
        clearInterval(timerFirst);
    });
    
    // 슬라이스쇼 밖으로 마우스 커서를 옮기면 다시 슬라이드 쇼를 작동시킴
    slideshowSlideFirst.addEventListener('mouseleave', ()=>{
        setFirstTimeDelay();
    });

    // 위와 마찬가지로 인디케이션을 클릭할 경우에도 동일하게 작동되게 함
    indicatorsFirst.forEach((indicator)=>{
        indicator.addEventListener('mouseenter', ()=>{
            clearInterval(timerFirst);
        })
    });

    // 인디케이션을 클릭하면 화면도 움직이게 함
    for(let i = 0; i < slideFirstCount; i++) {
        indicatorsFirst[i].addEventListener('click',(e)=>{
            e.preventDefault();
            moveFirstCamera(i);
        });
    }

    /* SECOND SLIDESHOW (상기 동일) */
    for (let i = 0; i < slideSecondCount; i++) {
        let newPosition = i * 300 + 'px';
        slidesSecond[i].style.left = newPosition;
    }

    function moveSecondCamera(index) {
        currentSecondIndex = index;
        let newSecondCameraPosition = currentSecondIndex * -300 + 'px';
        slideshowSlideSecond.style.left = newSecondCameraPosition;
        indicatorsSecond.forEach((obj)=>{
            obj.classList.remove('active');
        });
        indicatorsSecond[index].classList.add('active');
    }

    function setSecondTimeDelay() {
        timerSecond = setInterval(function(){
            newSecondIndex = (currentSecondIndex + 1) % slideSecondCount;
            moveSecondCamera(newSecondIndex)
        }, 3000)
    }

    setSecondTimeDelay();

    prevSecond.addEventListener('click',(e)=>{
        e.preventDefault();
        let newIndex = currentSecondIndex - 1;
        if (newIndex < 0) {
            newIndex = slideSecondCount - 1 ;
        }
        moveSecondCamera(newIndex);
    });

    nextSecond.addEventListener('click',(e)=>{
        e.preventDefault();
        let newIndex = currentSecondIndex + 1;
        if (newIndex > slideSecondCount - 1) {
            newIndex = 0;
        }
        moveSecondCamera(newIndex);
    });

    prevSecond.addEventListener('mouseenter', ()=>{
        clearInterval(timerSecond);
    })

    nextSecond.addEventListener('mouseenter', ()=>{
        clearInterval(timerSecond);
    })

    slideshowSlideSecond.addEventListener('mouseenter', ()=>{
        clearInterval(timerSecond);
    });
    
    slideshowSlideSecond.addEventListener('mouseleave', ()=>{
        setSecondTimeDelay();
    });

    indicatorsSecond.forEach((indicator)=>{
        indicator.addEventListener('mouseenter', ()=>{
            clearInterval(timerSecond);
        })
    });

    for(let i = 0; i < slideSecondCount; i++) {
        indicatorsSecond[i].addEventListener('click',(e)=>{
            e.preventDefault();
            moveSecondCamera(i);
        });
    }
}