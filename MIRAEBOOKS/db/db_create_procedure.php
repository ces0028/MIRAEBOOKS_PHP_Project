<?php
// 초기 데이터를 자동적으로 입력하기 위한 프로시저를 생성
function create_procedure($con, $procedure_name) {
    $procedure_flag = false;
    // 데이터베이스에 생성된 모든 프로시저를 불러옴
    $sql_show_procedure = "SHOW PROCEDURE STATUS WHERE DB = 'miraebooksdb'";
    $result = mysqli_query($con, $sql_show_procedure) or die('프로시저 조회 실패'.mysqli_error($con));

    // 프로시저를 하나씩 불러와서 그것이 만들고자 하는 프로시저명과 같은 지를 비교
    while ($row = mysqli_fetch_row($result)) {
        // 프로시저명과 같다 = 이미 만들어진 프로시저다 => 중복되지 않기 위해서 생성하지 않음
        if ($row[1] === $procedure_name) { 
            $procedure_flag = true;
            break;
        }
    }

    // 해당되는 프로시저가 없을 경우에 한해서 프로시저를 생성
    if ($procedure_flag === false) {
        // 주어진 프로시저문에 따라서 쿼리문을 달리함
        switch ($procedure_name) {
            // 회원 정보 생성
            case 'member_procedure':
                $sql = "
                    CREATE PROCEDURE member_procedure()
                    BEGIN
                        INSERT INTO members VALUES 
                        ('tumulus6928', 'tumulus6928', '황우리', 'F', 'tumulus6928@gmail.com', '010-6928-6928', '2022-10-16 09:40', 1), 
                        ('peroratum1173', 'peroratum1173', '최나길', 'M', 'peroratum1173@naver.com', '010-1173-1173', '2022-10-17 03:21', 1), 
                        ('evolvere8580', 'evolvere8580', '노우람', 'M', 'evolvere8580@gmail.com', '010-8580-8580', '2022-10-17 19:58', 1), 
                        ('generalis5199', 'generalis5199', '신우람', 'M', 'generalis5199@gmail.com', '010-5199-5199', '2022-11-04 19:30', 1), 
                        ('saeculi4589', 'saeculi4589', '안창희', 'M', 'saeculi4589@gmail.com', '010-4589-4589', '2022-11-07 23:11', 1), 
                        ('adducet1692', 'adducet1692', '오하루', 'F', 'adducet1692@naver.com', '010-1692-1692', '2022-11-19 11:30', 1), 
                        ('festinate4997', 'festinate4997', '임보다', 'F', 'festinate4997@naver.com', '010-4997-4997', '2022-11-20 12:26', 1), 
                        ('agitur3341', 'agitur3341', '김한길', 'M', 'agitur3341@gmail.com', '010-3341-3341', '2022-11-20 12:30', 1), 
                        ('maxime4225', 'maxime4225', '윤조은', 'F', 'maxime4225@nate.com', '010-4225-4225', '2022-11-27 16:40', 1), 
                        ('docebit5990', 'docebit5990', '백봄', 'F', 'docebit5990@nate.com', '010-5990-5990', '2022-11-29 18:31', 1), 
                        ('ferrum8717', 'ferrum8717', '이소라', 'F', 'ferrum8717@naver.com', '010-8717-8717', '2022-11-30 19:45', 1), 
                        ('navale7400', 'navale7400', '설은샘', 'F', 'navale7400@gmail.com', '010-7400-7400', '2022-12-02 06:38', 1), 
                        ('colligunt4729', 'colligunt4729', '강나길', 'M', 'colligunt4729@gmail.com', '010-4729-4729', '2022-12-02 21:00', 1), 
                        ('misit3890', 'misit3890', '정아리', 'F', 'misit3890@gmail.com', '010-3890-3890', '2022-12-04 20:12', 1), 
                        ('eligere2256', 'eligere2256', '정샘', 'M', 'eligere2256@gmail.com', '010-2256-2256', '2022-12-06 23:02', 1), 
                        ('scholae6698', 'scholae6698', '노현준', 'M', 'scholae6698@gmail.com', '010-6698-6698', '2022-12-09 22:45', 1), 
                        ('ipsum2343', 'ipsum2343', '김소현', 'F', 'ipsum2343@gmail.com', '010-2343-2343', '2022-12-14 01:05', 1), 
                        ('orbis4126', 'orbis4126', '오재환', 'M', 'orbis4126@gmail.com', '010-4126-4126', '2022-12-14 09:18', 1), 
                        ('tulit2920', 'tulit2920', '안영빈', 'F', 'tulit2920@nate.com', '010-2920-2920', '2022-12-18 17:21', 1), 
                        ('praxi8960', 'praxi8960', '고유빈', 'F', 'praxi8960@nate.com', '010-8960-8960', '2022-12-19 09:19', 1), 
                        ('nomen8482', 'nomen8482', '서희옥', 'F', 'nomen8482@naver.com', '010-8482-8482', '2022-12-21 11:26', 1), 
                        ('flos8567', 'flos8567', '이버들', 'M', 'flos8567@naver.com', '010-8567-8567', '2022-12-22 01:01', 1), 
                        ('hominis2175', 'hominis2175', '김힘찬', 'M', 'hominis2175@nate.com', '010-2175-2175', '2022-12-24 02:06', 1), 
                        ('forsan9649', 'forsan9649', '서은비', 'F', 'forsan9649@gmail.com', '010-9649-9649', '2022-12-24 08:15', 1), 
                        ('ripam4814', 'ripam4814', '최나봄', 'F', 'ripam4814@nate.com', '010-4814-4814', '2022-12-24 11:28', 1), 
                        ('taberna7857', 'taberna7857', '남종진', 'M', 'taberna7857@naver.com', '010-7857-7857', '2022-12-29 09:44', 1), 
                        ('celebre8661', 'celebre8661', '윤준석', 'F', 'celebre8661@naver.com', '010-8661-8661', '2022-12-31 12:59', 1), 
                        ('apta4201', 'apta4201', '박인준', 'M', 'apta4201@gmail.com', '010-4201-4201', '2023-01-05 07:56', 1), 
                        ('past1383', 'past1383', '손소미', 'F', 'past1383@naver.com', '010-1383-1383', '2023-01-07 15:15', 1), 
                        ('terra5119', 'terra5119', '김세윤', 'F', 'terra5119@gmail.com', '010-5119-5119', '2023-01-14 01:16', 1);
                    END";
                break;
            // ebook 정보 생성
            case 'ebook_procedure':
                $sql = "
                    CREATE PROCEDURE ebook_procedure()
                    BEGIN
                    INSERT INTO ebook (ebook_lang, ebook_title, ebook_author, ebook_bookcover_name, ebook_bookcover_path, ebook_content_name, ebook_content_path, ebook_file_name, ebook_file_path) VALUES
                    ('en', 'Romeo and Juliet', 'William Shakespeare', 'img_bk_001_en.jpg', '/MIRAEBOOKS/source/book_cover/', '001_en.html', '/MIRAEBOOKS/source/html/', '001_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'A Room with a View', 'E. M. Forster', 'img_bk_002_en.jpg', '/MIRAEBOOKS/source/book_cover/', '002_en.html', '/MIRAEBOOKS/source/html/', '002_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'Middlemarch', 'George Eliot', 'img_bk_003_en.jpg', '/MIRAEBOOKS/source/book_cover/', '003_en.html', '/MIRAEBOOKS/source/html/', '003_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'Little Women', 'Louisa May Alcott', 'img_bk_004_en.jpg', '/MIRAEBOOKS/source/book_cover/', '004_en.html', '/MIRAEBOOKS/source/html/', '004_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'The Enchanted April', 'Elizabeth Von Arnim', 'img_bk_005_en.jpg', '/MIRAEBOOKS/source/book_cover/', '005_en.html', '/MIRAEBOOKS/source/html/', '005_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'The Complete Works of William Shakespeare', 'William Shakespeare', 'img_bk_006_en.jpg', '/MIRAEBOOKS/source/book_cover/', '006_en.html', '/MIRAEBOOKS/source/html/', '006_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'The Blue Castle', 'L. M. Montgomery', 'img_bk_007_en.jpg', '/MIRAEBOOKS/source/book_cover/', '007_en.html', '/MIRAEBOOKS/source/html/', '007_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'Moby Dick', 'Herman Melville', 'img_bk_008_en.jpg', '/MIRAEBOOKS/source/book_cover/', '008_en.html', '/MIRAEBOOKS/source/html/', '008_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'The Adventures of Ferdinand Count Fathom', 'T. Smollett', 'img_bk_009_en.jpg', '/MIRAEBOOKS/source/book_cover/', '009_en.html', '/MIRAEBOOKS/source/html/', '009_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'Cranford', 'Elizabeth Cleghorn Gaskell', 'img_bk_010_en.jpg', '/MIRAEBOOKS/source/book_cover/', '010_en.html', '/MIRAEBOOKS/source/html/', '010_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'The Expedition of Humphry Clinker', 'T. Smollett', 'img_bk_011_en.jpg', '/MIRAEBOOKS/source/book_cover/', '011_en.html', '/MIRAEBOOKS/source/html/', '011_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'The Adventures of Roderick Random', 'T. Smollett', 'img_bk_012_en.jpg', '/MIRAEBOOKS/source/book_cover/', '012_en.html', '/MIRAEBOOKS/source/html/', '012_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'History of Tom Jones, a Foundling', 'Henry Fielding', 'img_bk_013_en.jpg', '/MIRAEBOOKS/source/book_cover/', '013_en.html', '/MIRAEBOOKS/source/html/', '013_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'Twenty Years After', 'Alexandre Dumas', 'img_bk_014_en.jpg', '/MIRAEBOOKS/source/book_cover/', '014_en.html', '/MIRAEBOOKS/source/html/', '014_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'Alices Adventures in Wonderland', 'Lewis Carroll', 'img_bk_015_en.jpg', '/MIRAEBOOKS/source/book_cover/', '015_en.html', '/MIRAEBOOKS/source/html/', '015_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'The Picture of Dorian Gray', 'Oscar Wilde', 'img_bk_016_en.jpg', '/MIRAEBOOKS/source/book_cover/', '016_en.html', '/MIRAEBOOKS/source/html/', '016_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'The Adventures of Sherlock Holmes', 'Arthur Conan Doyle', 'img_bk_017_en.jpg', '/MIRAEBOOKS/source/book_cover/', '017_en.html', '/MIRAEBOOKS/source/html/', '017_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'A Tale of Two Cities', 'Charles Dickens', 'img_bk_018_en.jpg', '/MIRAEBOOKS/source/book_cover/', '018_en.html', '/MIRAEBOOKS/source/html/', '018_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'Grimms Fairy Tales', 'Jacob Grimm and Wilhelm Grimm', 'img_bk_019_en.jpg', '/MIRAEBOOKS/source/book_cover/', '019_en.html', '/MIRAEBOOKS/source/html/', '019_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'The Scarlet Letter', 'Nathaniel Hawthorne', 'img_bk_020_en.jpg', '/MIRAEBOOKS/source/book_cover/', '020_en.html', '/MIRAEBOOKS/source/html/', '020_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'The Great Gatsby', 'F. Scott Fitzgerald', 'img_bk_021_en.jpg', '/MIRAEBOOKS/source/book_cover/', '021_en.html', '/MIRAEBOOKS/source/html/', '021_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'Dracula', 'Bram Stoker', 'img_bk_022_en.jpg', '/MIRAEBOOKS/source/book_cover/', '022_en.html', '/MIRAEBOOKS/source/html/', '022_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'The Brothers Karamazov', 'Fyodor Dostoyevsky', 'img_bk_023_en.jpg', '/MIRAEBOOKS/source/book_cover/', '023_en.html', '/MIRAEBOOKS/source/html/', '023_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'Great Expectations', 'Charles Dickens', 'img_bk_024_en.jpg', '/MIRAEBOOKS/source/book_cover/', '024_en.html', '/MIRAEBOOKS/source/html/', '024_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'Crime and Punishment', 'Fyodor Dostoyevsky', 'img_bk_025_en.jpg', '/MIRAEBOOKS/source/book_cover/', '025_en.html', '/MIRAEBOOKS/source/html/', '025_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'Ulysses', 'James Joyce', 'img_bk_026_en.jpg', '/MIRAEBOOKS/source/book_cover/', '026_en.html', '/MIRAEBOOKS/source/html/', '026_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'The Strange Case of Dr. Jekyll and Mr. Hyde', 'Robert Louis Stevenson', 'img_bk_027_en.jpg', '/MIRAEBOOKS/source/book_cover/', '027_en.html', '/MIRAEBOOKS/source/html/', '027_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'Metamorphosis', 'Franz Kafka', 'img_bk_028_en.jpg', '/MIRAEBOOKS/source/book_cover/', '028_en.html', '/MIRAEBOOKS/source/html/', '028_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'A Dolls House', 'Henrik Ibsen', 'img_bk_029_en.jpg', '/MIRAEBOOKS/source/book_cover/', '029_en.html', '/MIRAEBOOKS/source/html/', '029_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'War and Peace', 'Leo Tolstoy', 'img_bk_030_en.jpg', '/MIRAEBOOKS/source/book_cover/', '030_en.html', '/MIRAEBOOKS/source/html/', '030_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'Jane Eyre', 'Charlotte Brontë', 'img_bk_031_en.jpg', '/MIRAEBOOKS/source/book_cover/', '031_en.html', '/MIRAEBOOKS/source/html/', '031_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'The Yellow Wallpaper', 'Charlotte Perkins Gilman', 'img_bk_032_en.jpg', '/MIRAEBOOKS/source/book_cover/', '032_en.html', '/MIRAEBOOKS/source/html/', '032_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'Adventures of Huckleberry Finn', 'Mark Twain', 'img_bk_033_en.jpg', '/MIRAEBOOKS/source/book_cover/', '033_en.html', '/MIRAEBOOKS/source/html/', '033_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', 'こころ', '夏目漱石', 'img_bk_034_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '034_jp.html', '/MIRAEBOOKS/source/html/', '034_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '吾輩は猫である', '夏目漱石', 'img_bk_035_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '035_jp.html', '/MIRAEBOOKS/source/html/', '035_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '鴎外博士の追憶', '内田魯庵', 'img_bk_036_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '036_jp.html', '/MIRAEBOOKS/source/html/', '036_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '異邦人に就て', '坂口安吾', 'img_bk_037_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '037_jp.html', '/MIRAEBOOKS/source/html/', '037_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '孤閨瞋火', '山口芳光', 'img_bk_038_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '038_jp.html', '/MIRAEBOOKS/source/html/', '038_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '地を掘る人達に', '百田宗治', 'img_bk_039_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '039_jp.html', '/MIRAEBOOKS/source/html/', '039_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '寒林小唱', '三好達治', 'img_bk_040_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '040_jp.html', '/MIRAEBOOKS/source/html/', '040_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '飯田蛇笏', '芥川龍之介', 'img_bk_041_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '041_jp.html', '/MIRAEBOOKS/source/html/', '041_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '大阪の朝', '安西冬衛', 'img_bk_042_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '042_jp.html', '/MIRAEBOOKS/source/html/', '042_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '水の上', '安西冬衛', 'img_bk_043_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '043_jp.html', '/MIRAEBOOKS/source/html/', '043_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '芭蕉雑記', '芥川龍之介', 'img_bk_044_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '044_jp.html', '/MIRAEBOOKS/source/html/', '044_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', 'Sの背中', '梅崎春生', 'img_bk_045_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '045_jp.html', '/MIRAEBOOKS/source/html/', '045_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '魔法人形', '江戸川乱歩', 'img_bk_046_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '046_jp.html', '/MIRAEBOOKS/source/html/', '046_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '坊っちゃん', '夏目漱石', 'img_bk_047_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '047_jp.html', '/MIRAEBOOKS/source/html/', '047_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '江戸の火術', '野村胡堂', 'img_bk_048_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '048_jp.html', '/MIRAEBOOKS/source/html/', '048_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '学問のすすめ', '福沢諭吉', 'img_bk_049_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '049_jp.html', '/MIRAEBOOKS/source/html/', '049_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '人間椅子', '江戸川乱歩', 'img_bk_050_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '050_jp.html', '/MIRAEBOOKS/source/html/', '050_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', 'パノラマ島綺譚', '江戸川乱歩', 'img_bk_051_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '051_jp.html', '/MIRAEBOOKS/source/html/', '051_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '羅生門', '芥川龍之介', 'img_bk_052_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '052_jp.html', '/MIRAEBOOKS/source/html/', '052_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '舞姫', '石橋忍月', 'img_bk_053_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '053_jp.html', '/MIRAEBOOKS/source/html/', '053_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', 'カインの末裔', '有島武郎', 'img_bk_054_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '054_jp.html', '/MIRAEBOOKS/source/html/', '054_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '春の潮', '伊藤左千夫', 'img_bk_055_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '055_jp.html', '/MIRAEBOOKS/source/html/', '055_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '隣の嫁', '伊藤左千夫', 'img_bk_056_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '056_jp.html', '/MIRAEBOOKS/source/html/', '056_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '火星兵団', '海野十三', 'img_bk_057_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '057_jp.html', '/MIRAEBOOKS/source/html/', '057_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', 'Ｄ坂の殺人事件', '江戸川乱歩', 'img_bk_058_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '058_jp.html', '/MIRAEBOOKS/source/html/', '058_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '文壇昔ばなし', '谷崎潤一郎', 'img_bk_059_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '059_jp.html', '/MIRAEBOOKS/source/html/', '059_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '私の探偵小説', '坂口安吾', 'img_bk_060_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '060_jp.html', '/MIRAEBOOKS/source/html/', '060_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', 'わが半生を語る', '太宰治', 'img_bk_061_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '061_jp.html', '/MIRAEBOOKS/source/html/', '061_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '緒方氏を殺した者', '太宰治', 'img_bk_062_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '062_jp.html', '/MIRAEBOOKS/source/html/', '062_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '山月記', '中島敦', 'img_bk_063_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '063_jp.html', '/MIRAEBOOKS/source/html/', '063_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '銀河鉄道の夜', '宮沢賢治', 'img_bk_064_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '064_jp.html', '/MIRAEBOOKS/source/html/', '064_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '蛙のゴム靴', '宮沢賢治', 'img_bk_065_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '065_jp.html', '/MIRAEBOOKS/source/html/', '065_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', 'マスク', '菊池寛', 'img_bk_066_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '066_jp.html', '/MIRAEBOOKS/source/html/', '066_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '그를 보내며', '한용운', 'img_bk_067_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '067_kr.html', '/MIRAEBOOKS/source/html/', '067_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '운수 좋은 날', '현진건', 'img_bk_068_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '068_kr.html', '/MIRAEBOOKS/source/html/', '068_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '동백꽃', '김유정', 'img_bk_069_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '069_kr.html', '/MIRAEBOOKS/source/html/', '069_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '이십세의 야망가', '김동인', 'img_bk_070_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '070_kr.html', '/MIRAEBOOKS/source/html/', '070_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '경희', '나정월', 'img_bk_071_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '071_kr.html', '/MIRAEBOOKS/source/html/', '071_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '이영녀', '김우진', 'img_bk_072_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '072_kr.html', '/MIRAEBOOKS/source/html/', '072_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '메밀꽃 필 무렵', '이효석', 'img_bk_073_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '073_kr.html', '/MIRAEBOOKS/source/html/', '073_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '광염 소나타', '김동인', 'img_bk_074_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '074_kr.html', '/MIRAEBOOKS/source/html/', '074_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '이상한 선생님', '채만식', 'img_bk_075_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '075_kr.html', '/MIRAEBOOKS/source/html/', '075_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '약한 자의 슬픔', '김동인', 'img_bk_076_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '076_kr.html', '/MIRAEBOOKS/source/html/', '076_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '봄봄', '김유정', 'img_bk_077_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '077_kr.html', '/MIRAEBOOKS/source/html/', '077_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '봉별기', '이상', 'img_bk_078_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '078_kr.html', '/MIRAEBOOKS/source/html/', '078_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '날개', '이상', 'img_bk_079_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '079_kr.html', '/MIRAEBOOKS/source/html/', '079_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '태평천하', '채만식', 'img_bk_080_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '080_kr.html', '/MIRAEBOOKS/source/html/', '080_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', 'B사감과 러브레터', '현진건', 'img_bk_081_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '081_kr.html', '/MIRAEBOOKS/source/html/', '081_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '인간문제', '강경애', 'img_bk_082_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '082_kr.html', '/MIRAEBOOKS/source/html/', '082_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '빈처', '현진건', 'img_bk_083_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '083_kr.html', '/MIRAEBOOKS/source/html/', '083_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '내마음 고요히 흐른 봄길 우에', '김영랑', 'img_bk_084_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '084_kr.html', '/MIRAEBOOKS/source/html/', '084_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '어머니와 딸', '강경애', 'img_bk_085_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '085_kr.html', '/MIRAEBOOKS/source/html/', '085_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '물레방아', '나도향', 'img_bk_086_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '086_kr.html', '/MIRAEBOOKS/source/html/', '086_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '무영탑', '현진건', 'img_bk_087_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '087_kr.html', '/MIRAEBOOKS/source/html/', '087_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '상록수', '심훈', 'img_bk_088_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '088_kr.html', '/MIRAEBOOKS/source/html/', '088_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '조선혁명선언', '신채호', 'img_bk_089_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '089_kr.html', '/MIRAEBOOKS/source/html/', '089_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '조선상고사', '신채호', 'img_bk_090_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '090_kr.html', '/MIRAEBOOKS/source/html/', '090_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '감자', '김동인', 'img_bk_091_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '091_kr.html', '/MIRAEBOOKS/source/html/', '091_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '여자의 일생', '채만식', 'img_bk_092_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '092_kr.html', '/MIRAEBOOKS/source/html/', '092_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '허생전', '채만식', 'img_bk_093_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '093_kr.html', '/MIRAEBOOKS/source/html/', '093_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '별 헤는 밤', '윤동주', 'img_bk_094_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '094_kr.html', '/MIRAEBOOKS/source/html/', '094_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '원고료 이백원', '강경애', 'img_bk_095_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '095_kr.html', '/MIRAEBOOKS/source/html/', '095_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '단종애사', '이광수', 'img_bk_096_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '096_kr.html', '/MIRAEBOOKS/source/html/', '096_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '김연실전', '김동인', 'img_bk_097_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '097_kr.html', '/MIRAEBOOKS/source/html/', '097_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '이태리영화와 여배우', '박인환', 'img_bk_098_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '098_kr.html', '/MIRAEBOOKS/source/html/', '098_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '탁류', '채만식', 'img_bk_099_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '099_kr.html', '/MIRAEBOOKS/source/html/', '099_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '어둠', '강경애', 'img_bk_100_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '100_kr.html', '/MIRAEBOOKS/source/html/', '100_kr.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '兄貴のような心持', '芥川龍之介', 'img_bk_101_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '101_jp.html', '/MIRAEBOOKS/source/html/', '101_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('jp', '格さんと食慾', '芥川龍之介', 'img_bk_102_jp.jpg', '/MIRAEBOOKS/source/book_cover/', '102_jp.html', '/MIRAEBOOKS/source/html/', '102_jp.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('en', 'A Christmas Carol in Prose', 'Charles Dickens', 'img_bk_103_en.jpg', '/MIRAEBOOKS/source/book_cover/', '103_en.html', '/MIRAEBOOKS/source/html/', '103_en.pdf', '/MIRAEBOOKS/source/pdf/'),
                    ('kr', '조선역사상일천년래제일대사건', '신채호', 'img_bk_104_kr.jpg', '/MIRAEBOOKS/source/book_cover/', '104_kr.html', '/MIRAEBOOKS/source/html/', '104_kr.pdf', '/MIRAEBOOKS/source/pdf/');
                    END";
                break;
            // 공지사항 게시판 게시물 생성
            case 'notice_board_procedure':
                $sql = "
                    CREATE PROCEDURE notice_board_procedure()
                    BEGIN
                        INSERT INTO board (board_type, board_member_id, board_title, board_content, board_register_date) VALUES
                        ('notice', 'admin0028', '미래북스 사이트가 오픈했습니다', '저작권이 만료된 책을 자유롭게 볼 수 있는 공간, 미래북스가 오픈했습니다.\n앞으로 잘 부탁드립니다.', '2022-08-12 09:00'),
                        ('notice', 'admin0028', '공지사항2', '공지사항2', '2022-09-19 09:00'),
                        ('notice', 'admin0028', '공지사항3', '공지사항3', '2022-10-31 09:00'),
                        ('notice', 'admin0028', '공지사항4', '공지사항4', '2022-12-12 09:00'),
                        ('notice', 'admin0028', '2023년 새해가 밝았습니다', '2023년 계묘년의 해가 밝았습니다.\n2023년도 잘 부탁드립니다.', '2023-01-01 00:00'),
                        ('notice', 'admin0028', '공지사항6', '공지사항6', '2023-01-17 09:00');
                    END";
                break;
            // 자유 게시판 게시물 생성
            case 'free_board_procedure':
                $sql = "
                    CREATE PROCEDURE free_board_procedure()
                    BEGIN
                        INSERT INTO board (board_type, board_member_id, board_title, board_content, board_register_date, board_file_name, board_file_type, board_file_path, board_file_saved_name) VALUES
                        ('free', 'tumulus6928', '자유게시물1', '자유게시물1', '2022-11-16 09:40', '001.jpg', 'image/jpeg', 'C:/xampp/htdocs/MIRAEBOOKS/data/', '001.jpg'), 
                        ('free', 'peroratum1173', '자유게시물2', '자유게시물2', '2022-11-17 03:21', '002.jpg', 'image/jpeg', 'C:/xampp/htdocs/MIRAEBOOKS/data/', '002.jpg'), 
                        ('free', 'evolvere8580', '자유게시물3', '자유게시물3', '2022-11-17 19:58', '003.jpg', 'image/jpeg', 'C:/xampp/htdocs/MIRAEBOOKS/data/', '003.jpg'), 
                        ('free', 'generalis5199', '자유게시물4', '자유게시물4', '2022-12-04 19:30', '004.jpg', 'image/jpeg', 'C:/xampp/htdocs/MIRAEBOOKS/data/', '004.jpg'), 
                        ('free', 'saeculi4589', '자유게시물5', '자유게시물5', '2022-12-07 23:11', '005.jpg', 'image/jpeg', 'C:/xampp/htdocs/MIRAEBOOKS/data/', '005.jpg'), 
                        ('free', 'adducet1692', '자유게시물6', '자유게시물6', '2022-12-19 11:30', '006.jpg', 'image/jpeg', 'C:/xampp/htdocs/MIRAEBOOKS/data/', '006.jpg'), 
                        ('free', 'festinate4997', '자유게시물7', '자유게시물7', '2022-12-20 12:26', '007.jpg', 'image/jpeg', 'C:/xampp/htdocs/MIRAEBOOKS/data/', '007.jpg'), 
                        ('free', 'agitur3341', '자유게시물8', '자유게시물8', '2022-12-20 12:30', '008.jpg', 'image/jpeg', 'C:/xampp/htdocs/MIRAEBOOKS/data/', '008.jpg'), 
                        ('free', 'maxime4225', '자유게시물9', '자유게시물9', '2022-12-27 16:40', '009.jpg', 'image/jpeg', 'C:/xampp/htdocs/MIRAEBOOKS/data/', '009.jpg'), 
                        ('free', 'docebit5990', '자유게시물10', '자유게시물10', '2022-12-29 18:31', '010.jpg', 'image/jpeg', 'C:/xampp/htdocs/MIRAEBOOKS/data/', '010.jpg'), 
                        ('free', 'ferrum8717', '자유게시물11', '자유게시물11', '2022-12-30 19:45', '011.jpg', 'image/jpeg', 'C:/xampp/htdocs/MIRAEBOOKS/data/', '011.jpg'), 
                        ('free', 'navale7400', '자유게시물12', '자유게시물12', '2023-01-02 06:38', '', '', '', '');
                    END";
                break;
            // 문의 게시판 게시물 생성        
            case 'question_board_procedure':
                $sql = "
                    CREATE PROCEDURE question_board_procedure()
                    BEGIN
                        INSERT INTO qna_board_question (qna_member_id, qna_question_title, qna_question_content, qna_question_register_date) VALUES
                        ('tumulus6928', 'QUESTION 1', 'QnA 게시판 질문입니다', '2022-11-16 09:40'), 
                        ('peroratum1173', 'QUESTION 2', 'QnA 게시판 질문입니다', '2022-11-17 03:21'), 
                        ('evolvere8580', 'QUESTION 3', 'QnA 게시판 질문입니다', '2022-11-17 19:58'), 
                        ('generalis5199', 'QUESTION 4', 'QnA 게시판 질문입니다', '2022-12-04 19:30'), 
                        ('saeculi4589', 'QUESTION 5', 'QnA 게시판 질문입니다', '2022-12-07 23:11'), 
                        ('adducet1692', 'QUESTION 6', 'QnA 게시판 질문입니다', '2022-12-19 11:30'), 
                        ('festinate4997', 'QUESTION 7', 'QnA 게시판 질문입니다', '2022-12-20 12:26'), 
                        ('agitur3341', 'QUESTION 8', 'QnA 게시판 질문입니다', '2022-12-20 12:30'), 
                        ('maxime4225', 'QUESTION 9', 'QnA 게시판 질문입니다', '2022-12-27 16:40'), 
                        ('docebit5990', 'QUESTION 10', 'QnA 게시판 질문입니다', '2022-12-29 18:31'), 
                        ('ferrum8717', 'QUESTION 11', 'QnA 게시판 질문입니다', '2022-12-30 19:45'), 
                        ('navale7400', 'QUESTION 12', 'QnA 게시판 질문입니다', '2023-01-18 06:38');
                    END";
                break;
            // 문의 게시판 게시물의 답변 생성
            case 'answer_board_procedure':
                $sql = "
                    CREATE PROCEDURE answer_board_procedure()
                    BEGIN
                        INSERT INTO qna_board_answer VALUES
                        (1, 'QnA 게시판 답변입니다', '2022-11-17 09:40'), 
                        (2, 'QnA 게시판 답변입니다', '2022-11-18 03:21'), 
                        (3, 'QnA 게시판 답변입니다', '2022-11-18 19:58'), 
                        (4, 'QnA 게시판 답변입니다', '2022-12-05 19:30'), 
                        (5, 'QnA 게시판 답변입니다', '2022-12-08 23:11'), 
                        (6, 'QnA 게시판 답변입니다', '2022-12-20 11:30'), 
                        (7, 'QnA 게시판 답변입니다', '2022-12-21 12:26'), 
                        (8, 'QnA 게시판 답변입니다', '2022-12-22 12:30'), 
                        (9, 'QnA 게시판 답변입니다', '2022-12-28 16:40'), 
                        (10, 'QnA 게시판 답변입니다', '2022-12-30 18:31'), 
                        (11, 'QnA 게시판 답변입니다', '2022-12-31 19:45');
                    END";
                break;
            // 요청 게시판 게시물 생성
            case 'request_board_procedure':
                $sql = "
                    CREATE PROCEDURE request_board_procedure()
                    BEGIN
                        INSERT INTO request_board (request_member_id, request_lang, request_title, request_author, request_register_date, request_result) VALUES
                        ('evolvere8580', 'jp', '兄貴のような心持', '芥川龍之介', '2022-12-11 07:57', 3),
                        ('orbis4126', 'jp', '格さんと食慾', '芥川龍之介', '2022-12-14 23:16', 3),
                        ('festinate4997', 'en', 'A Christmas Carol in Prose', 'Charles Dickens', '2022-12-19 19:38', 3),
                        ('past1383', 'kr', '조선역사상일천년래제일대사건', '신채호', '2022-12-30 16:06', 3),
                        ('tumulus6928', 'en', 'Pride and Prejudice', 'Jane Austen', '2023-01-18 09:00', 0);
                    END";
                    break;
            // 게시판 내 댓글 생성
            case 'board_reply_procedure':
                $sql = "
                    CREATE PROCEDURE board_reply_procedure()
                    BEGIN
                        INSERT INTO board_reply (reply_post, reply_post_member_id, reply_member_id, reply_content, reply_register_date) VALUES
                        (1, 'admin0028', 'tumulus6928', '안녕하세요', '2022-10-16 09:40'), 
                        (1, 'admin0028', 'peroratum1173', '안녕하세요', '2022-10-17 03:21'), 
                        (1, 'admin0028', 'evolvere8580', '안녕하세요', '2022-10-17 19:58'), 
                        (1, 'admin0028', 'generalis5199', '안녕하세요', '2022-11-04 19:30'), 
                        (1, 'admin0028', 'saeculi4589', '안녕하세요', '2022-11-07 23:11'), 
                        (1, 'admin0028', 'adducet1692', '안녕하세요', '2022-11-19 11:30'), 
                        (1, 'admin0028', 'festinate4997', '안녕하세요', '2022-11-20 12:26'), 
                        (1, 'admin0028', 'agitur3341', '안녕하세요', '2022-11-20 12:30'), 
                        (1, 'admin0028', 'maxime4225', '안녕하세요', '2022-11-27 16:40'), 
                        (1, 'admin0028', 'docebit5990', '안녕하세요', '2022-11-29 18:31'), 
                        (1, 'admin0028', 'ferrum8717', '안녕하세요', '2022-11-30 19:45'), 
                        (1, 'admin0028', 'navale7400', '안녕하세요', '2022-12-02 06:38'), 
                        (1, 'admin0028', 'colligunt4729', '안녕하세요', '2022-12-02 21:00'), 
                        (1, 'admin0028', 'misit3890', '안녕하세요', '2022-12-04 20:12'), 
                        (1, 'admin0028', 'eligere2256', '안녕하세요', '2022-12-06 23:02'), 
                        (1, 'admin0028', 'scholae6698', '안녕하세요', '2022-12-09 22:45'), 
                        (1, 'admin0028', 'ipsum2343', '안녕하세요', '2022-12-14 01:05'), 
                        (1, 'admin0028', 'orbis4126', '안녕하세요', '2022-12-14 09:18'), 
                        (1, 'admin0028', 'tulit2920', '안녕하세요', '2022-12-18 17:21'), 
                        (1, 'admin0028', 'praxi8960', '안녕하세요', '2022-12-19 09:19'), 
                        (1, 'admin0028', 'nomen8482', '안녕하세요', '2022-12-21 11:26'), 
                        (1, 'admin0028', 'flos8567', '안녕하세요', '2022-12-22 01:01'), 
                        (1, 'admin0028', 'hominis2175', '안녕하세요', '2022-12-24 02:06'), 
                        (1, 'admin0028', 'forsan9649', '안녕하세요', '2022-12-24 08:15'), 
                        (1, 'admin0028', 'ripam4814', '안녕하세요', '2022-12-24 11:28'), 
                        (1, 'admin0028', 'taberna7857', '안녕하세요', '2022-12-29 09:44'), 
                        (1, 'admin0028', 'celebre8661', '안녕하세요', '2022-12-31 12:59'), 
                        (1, 'admin0028', 'apta4201', '안녕하세요', '2023-01-05 07:56'), 
                        (1, 'admin0028', 'past1383', '안녕하세요', '2023-01-07 15:15'), 
                        (1, 'admin0028', 'terra5119', '안녕하세요', '2023-01-14 01:16'), 
                        (3, 'admin0028', 'tumulus6928', '안녕하세요', '2022-10-31 09:40'), 
                        (3, 'admin0028', 'peroratum1173', '안녕하세요', '2022-10-31 13:21'), 
                        (3, 'admin0028', 'evolvere8580', '안녕하세요', '2022-10-31 19:58'), 
                        (4, 'admin0028', 'festinate4997', '안녕하세요', '2022-12-12 12:26'), 
                        (4, 'admin0028', 'agitur3341', '안녕하세요', '2022-12-12 12:30'), 
                        (4, 'admin0028', 'maxime4225', '안녕하세요', '2022-12-12 16:40'), 
                        (4, 'admin0028', 'docebit5990', '안녕하세요', '2022-12-12 18:31'), 
                        (4, 'admin0028', 'ferrum8717', '안녕하세요', '2022-12-12 19:45'), 
                        (4, 'admin0028', 'navale7400', '안녕하세요', '2022-12-12 06:38'), 
                        (4, 'admin0028', 'colligunt4729', '안녕하세요', '2022-12-12 21:00'), 
                        (4, 'admin0028', 'misit3890', '안녕하세요', '2022-12-12 20:12'), 
                        (4, 'admin0028', 'eligere2256', '안녕하세요', '2022-12-12 23:02'), 
                        (5, 'admin0028', 'flos8567', '새해 복 많이 받으세요', '2023-01-01 01:01'), 
                        (5, 'admin0028', 'ipsum2343', '새해 복 많이 받으세요', '2023-01-01 01:05'), 
                        (5, 'admin0028', 'hominis2175', '새해 복 많이 받으세요', '2023-01-01 02:06'), 
                        (5, 'admin0028', 'forsan9649', '새해 복 많이 받으세요', '2023-01-01 08:15'), 
                        (5, 'admin0028', 'orbis4126', '새해 복 많이 받으세요', '2023-01-01 09:18'), 
                        (5, 'admin0028', 'praxi8960', '새해 복 많이 받으세요', '2023-01-01 09:19'), 
                        (5, 'admin0028', 'taberna7857', '새해 복 많이 받으세요', '2023-01-01 09:44'), 
                        (5, 'admin0028', 'nomen8482', '새해 복 많이 받으세요', '2023-01-01 11:26'), 
                        (5, 'admin0028', 'ripam4814', '새해 복 많이 받으세요', '2023-01-01 11:28'), 
                        (5, 'admin0028', 'celebre8661', '새해 복 많이 받으세요', '2023-01-01 12:59'), 
                        (5, 'admin0028', 'tulit2920', '새해 복 많이 받으세요', '2023-01-01 17:21'), 
                        (5, 'admin0028', 'misit3890', '새해 복 많이 받으세요', '2023-01-01 20:12'), 
                        (5, 'admin0028', 'scholae6698', '새해 복 많이 받으세요', '2023-01-01 22:45'), 
                        (5, 'admin0028', 'eligere2256', '새해 복 많이 받으세요', '2023-01-01 23:02'),
                        (7, 'tumulus6928', 'evolvere8580', '답글입니다', '2022-11-17 09:40'), 
                        (8, 'peroratum1173', 'generalis5199', '답글입니다', '2022-11-18 03:21'), 
                        (9, 'evolvere8580', 'saeculi4589', '답글입니다', '2022-11-18 19:58'), 
                        (10, 'generalis5199', 'adducet1692', '답글입니다', '2022-12-05 19:30'), 
                        (11, 'saeculi4589', 'festinate4997', '답글입니다', '2022-12-08 23:11'), 
                        (12, 'adducet1692', 'agitur3341', '답글입니다', '2022-12-20 11:30'), 
                        (13, 'festinate4997', 'maxime4225', '답글입니다', '2022-12-21 12:26'), 
                        (14, 'agitur3341', 'docebit5990', '답글입니다', '2022-12-22 12:30'), 
                        (15, 'maxime4225', 'ferrum8717', '답글입니다', '2022-12-28 16:40'), 
                        (16, 'docebit5990', 'navale7400', '답글입니다', '2022-12-30 18:31'), 
                        (17, 'ferrum8717', 'colligunt4729', '답글입니다', '2022-12-31 19:45'), 
                        (18, 'navale7400', 'misit3890', '답글입니다', '2023-01-03 06:38');
                    END";
                break;
            // 주고 받은 메시지 생성
            case 'message_procedure':
                $sql = "
                    CREATE PROCEDURE message_procedure()
                    BEGIN
                        INSERT INTO message (message_send_id, message_receive_id, message_title, message_content, message_register_date) VALUES
                        ('admin0028', 'scholae6698', '보낸 메세지1', '보낸 메세지1', '2022-12-09 22:45'), 
                        ('admin0028', 'ipsum2343', '보낸 메세지2', '보낸 메세지2', '2022-12-14 01:05'), 
                        ('admin0028', 'orbis4126', '보낸 메세지3', '보낸 메세지3', '2022-12-14 09:18'), 
                        ('admin0028', 'tulit2920', '보낸 메세지4', '보낸 메세지4', '2022-12-18 17:21'), 
                        ('admin0028', 'praxi8960', '보낸 메세지5', '보낸 메세지5', '2022-12-19 09:19'), 
                        ('admin0028', 'nomen8482', '보낸 메세지6', '보낸 메세지6', '2022-12-21 11:26'), 
                        ('admin0028', 'flos8567', '보낸 메세지7', '보낸 메세지7', '2022-12-22 01:01'), 
                        ('admin0028', 'hominis2175', '보낸 메세지8', '보낸 메세지8', '2022-12-24 02:06'), 
                        ('admin0028', 'forsan9649', '보낸 메세지9', '보낸 메세지9', '2022-12-24 08:15'), 
                        ('admin0028', 'ripam4814', '보낸 메세지10', '보낸 메세지10', '2022-12-24 11:28'), 
                        ('admin0028', 'taberna7857', '보낸 메세지11', '보낸 메세지11', '2022-12-29 09:44'), 
                        ('admin0028', 'celebre8661', '보낸 메세지12', '보낸 메세지12', '2022-12-31 12:59'), 
                        ('admin0028', 'apta4201', '보낸 메세지13', '보낸 메세지13', '2023-01-05 07:56'), 
                        ('admin0028', 'past1383', '보낸 메세지14', '보낸 메세지14', '2023-01-07 15:15'), 
                        ('admin0028', 'terra5119', '보낸 메세지15', '보낸 메세지15', '2023-01-14 01:16'), 
                        ('scholae6698', 'admin0028', '받은 메시지1', '받은 메시지1', '2022-12-09 22:45'), 
                        ('ipsum2343', 'admin0028', '받은 메시지2', '받은 메시지2', '2022-12-14 01:05'), 
                        ('orbis4126', 'admin0028', '받은 메시지3', '받은 메시지3', '2022-12-14 09:18'), 
                        ('tulit2920', 'admin0028', '받은 메시지4', '받은 메시지4', '2022-12-18 17:21'), 
                        ('praxi8960', 'admin0028', '받은 메시지5', '받은 메시지5', '2022-12-19 09:19'), 
                        ('nomen8482', 'admin0028', '받은 메시지6', '받은 메시지6', '2022-12-21 11:26'), 
                        ('flos8567', 'admin0028', '받은 메시지7', '받은 메시지7', '2022-12-22 01:01'), 
                        ('hominis2175', 'admin0028', '받은 메시지8', '받은 메시지8', '2022-12-24 02:06'), 
                        ('forsan9649', 'admin0028', '받은 메시지9', '받은 메시지9', '2022-12-24 08:15'), 
                        ('ripam4814', 'admin0028', '받은 메시지10', '받은 메시지10', '2022-12-24 11:28'), 
                        ('taberna7857', 'admin0028', '받은 메시지11', '받은 메시지11', '2022-12-29 09:44'), 
                        ('celebre8661', 'admin0028', '받은 메시지12', '받은 메시지12', '2022-12-31 12:59'), 
                        ('apta4201', 'admin0028', '받은 메시지13', '받은 메시지13', '2023-01-05 07:56'), 
                        ('past1383', 'admin0028', '받은 메시지14', '받은 메시지14', '2023-01-07 15:15'), 
                        ('terra5119', 'admin0028', '받은 메시지15', '받은 메시지15', '2023-01-14 01:16');
                    END";
                break;
        }

        // 상기에서 선택한 쿼리문으로 프로시저를 생성
        if (mysqli_query($con, $sql)) {
            echo "<script>alert('{$procedure_name} 프로시저가 생성되었습니다.');</script>";
            // 생성한 프로시저를 호출하여 작동시킴
            call_procedure($con, $procedure_name);
        } else {
            echo "<script>alert('{$procedure_name} 프로시저가 생성되지 않았습니다.');</script>";
        }
    }
} 

function call_procedure($con, $procedure_name) {
    $sql = "CALL ".$procedure_name.";";
    $result = mysqli_query($con, $sql) or die("프로시저 호출 실패".mysqli_error($con));
    if ($result) {
    echo "<script>alert('{$procedure_name} 프로시저가 호출되었습니다.');</script>";
    } else {
    echo "<script>alert('{$procedure_name} 프로시저가 호출되지 않았습니다.');</script>";
    }
}