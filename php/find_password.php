<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/find_password.css">
    <script src="../js/find_password.js"></script>
</head>

<body>
    <div class="container">
        <!-- header -->
        <?php
        include('./header.php')
        ?>

        <!-- article -->
        <article>
            <form action="./find_password_update.php" method="post">
                <section class="section1">
                    <h2>비밀번호 찾기</h2>
                </section>
                <section class="section2">
                    <table>
                        <tr class="name">
                            <td>성(영문)</td>
                            <td><input type="text" name="first_name" /></td>
                            <td>이름(영문)</td>
                            <td><input type="text" name="last_name" /></td>
                        </tr>
                        <tr class="id">
                            <td>아이디</td>
                            <td colspan="3">
                                <input type="text" placeholder="영어 소문자, 숫자만 사용" name="id">
                            </td>
                        </tr>
                        <tr class="email">
                            <td>이메일</td>
                            <td colspan="3">
                                <input type="text" name="email1">
                                <span>@</span>
                                <input type="text" id="email_second" name="email2">
                                <select id="selected_email">
                                    <option value="">직접 입력</option>
                                    <option value="naver.com">naver.com</option>
                                    <option value="gmail.com">gmail.com</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="phone">
                            <td>휴대전화</td>
                            <td colspan="3">
                                <input type="number" name="mobile1" class="mobile" maxlength="3" oninput="numberMaxLength(this);">
                                <span>-</span>
                                <input type="number" name="mobile2" class="mobile" maxlength="4" oninput="numberMaxLength(this);">
                                <span>-</span>
                                <input type="number" name="mobile3" class="mobile" maxlength="4" oninput="numberMaxLength(this);">
                            </td>
                        </tr>
                    </table>
                </section>
                <section class="section3">
                    <input type="submit" value="찾기" id="find_password">
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