<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/login.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<script src="../js/login.js"></script>

<body>
    <div class="container">
        <!-- header -->
        <?php
        include('./header.php')
        ?>

        <!-- article -->
        <article>
            <form action="./login_check.php" method="post">
                <section class="section1">
                    <h2>로그인</h2>
                </section>
                <section class="section2">
                    <table>
                        <tr>
                            <td><input type="text" id="id" name="id" placeholder="아이디 입력" /></td>
                            <td rowspan="2"><input type="submit" id="login" value="로그인" /></td>
                        </tr>
                        <tr>
                            <td><input type="password" id="password" name="password" placeholder="비밀번호 입력" /></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="checkbox" id="idSaveCheck" />
                                <span>아이디 저장</span>
                            </td>
                        </tr>
                    </table>
                </section>
            </form>
            <section class="section3">
                <input type="button" id="findId" value="아이디 찾기" />
                <input type="button" id="findPassword" value="비밀번호 찾기" />
            </section>
        </article>

        <!-- footer -->
        <?php
        include('./footer.php')
        ?>
    </div>

</body>

</html>