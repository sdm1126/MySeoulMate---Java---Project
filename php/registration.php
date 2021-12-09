<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/registration.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../js/registration.js"></script>
</head>

<body>
    <div class="container">
        <!-- header -->
        <?php
        include('./header.php')
        ?>

        <!-- article -->
        <article>
            <!--   -->
            <form action="./register_update.php" onsubmit="return fregisterform_submit(this);" method="post">
                <section class="section1">
                    <h2>회원가입</h2>
                </section>
                <section class="section2">



                    <table>
                        <tr class="name">
                            <td>성(영문)</td>
                            <td><input type="text" name="first_name" id="first_name" onfocusout="first_name_function()"></td>
                            <td>이름(영문)</td>
                            <td><input type="text" name="second_name" id="second_name"></td>
                        </tr>
                        <tr class="gender">
                            <td>성별</td>
                            <td colspan="3">
                                <select id="" name="gender">
                                    <option value="Mr.">Mr.</option>
                                    <option value="Ms.">Ms.</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="id">
                            <td>아이디</td>
                            <td colspan="3">
                                <input type="text" placeholder="영어 소문자, 숫자만 사용" name="id" id="id">
                            </td>
                        </tr>
                        <tr class="password">
                            <td>비밀번호</td>
                            <td colspan="3">
                                <input type="password" placeholder="최소 8 자,문자,숫자,특수 문자포함" name="password" id="password_new">
                            </td>
                        </tr>
                        <tr class="password_confirm">
                            <td>비밀번호 확인</td>
                            <td colspan="3">
                                <input type="password" placeholder="비밀번호 확인 입력" name="password_re" id="password_check">
                            </td>
                        </tr>
                        <tr class="email">
                            <td>이메일</td>
                            <td colspan="3">
                                <input type="text" name="email1" id="email1">
                                <span>@</span>
                                <input type="text" name="email2" id="email_second">
                                <select name="" id="selected_email">
                                    <option value="">직접 입력</option>
                                    <option value="naver.com">naver.com</option>
                                    <option value="gmail.com">gmail.com</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="phone">
                            <td>휴대전화</td>
                            <td colspan="3">
                                <input type="number" name="mobile1" id="mobile1" class="mobile" maxlength="3" oninput="numberMaxLength(this);">
                                <span>-</span>
                                <input type="number" name="mobile2" id="mobile2" class="mobile" maxlength="4" oninput="numberMaxLength(this);">
                                <span>-</span>
                                <input type="number" name="mobile3" id="mobile3" class="mobile" maxlength="4" oninput="numberMaxLength(this);">
                            </td>
                        </tr>
                    </table>

                </section>
                <section class="section3">
                    <input type="submit" value="가입">
                </section>
            </form>
        </article>

        <!-- footer -->
        <?php
        include('./footer.php')
        ?>
    </div>
</body>

</html>