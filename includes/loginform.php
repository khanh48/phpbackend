<div class='modal-reg' id="modal-reg">
    <div class='modal-content'>
        <span class='close'>&times;</span>
        <header class='text-center mb-4'>
            <h1>Đăng ký</h1>
        </header>
        <form method='post'>
            <div class='row'>
                <div class='col-md-6'>
                    <label for='name'>Họ tên(*):</label>
                    <input class='form-control' type='text' id='name' name='fullName' aria-describedby='err' required />
                    <small class='text-danger' id='err'></small>
                </div>
                <div class='col-md-6'>
                    <label for='user-name'>Tên đăng nhập(*):</label>
                    <input class='form-control' type='text' id='user-name' name='userName' aria-describedby='err1'
                        required />
                    <small class='text-danger' id='err1'></small>
                </div>
            </div>

            <div class='row'>
                <div class='col-md-6'>
                    <label for='pwd'>Mật khẩu(*): </label>
                    <input class='form-control' type='password' id='pwd' name='pwd' aria-describedby='err3' required />
                    <small class='text-danger' id='err3'></small>
                </div>
                <div class='col-md-6'>
                    <label for='rpwd'>Nhập lại mật khẩu(*): </label>
                    <input class='form-control' type='password' id='rpwd' aria-describedby='err2' required />
                    <small class='text-danger' id='err2'></small>
                </div>
            </div>
            <div class='form-row'>
                <div class='m-auto'>
                    <input class='btn btn-primary mt-4 mb-3' type='submit' name='reg' value='Đăng ký'>
                </div>
            </div>
        </form>
    </div>
</div>
<div class='modal-login modal' id="modal-login">
    <div class="modal-dialog modal-lg">
        <div class='modal-content'>
            <header class='text-center mb-4 modal-header'>
                <h1 class="modal-title">Đăng nhập</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </header>
            <div class="modal-body">
                <form method='post' id="login" onsubmit="login()">
                    <div class='row'>
                        <small class='text-danger' id='err1-log'></small>
                        <div class='form-group col-md-12'>
                            <label for='user-name-log'>Tên đăng nhập(*):</label>
                            <input class='form-control' type='text' id='user-name-log' name='userNameLog'
                                aria-describedby='err1-log' required />
                        </div>
                    </div>
                    <div class='form-row'>
                        <div class='form-group col-md-12'>
                            <label for='pwd-log'>Mật khẩu(*): </label>
                            <input class='form-control' type='password' id='pwd-log' name='pwdLog'
                                aria-describedby='err3-log' required />
                            <small class='text-danger' id='err3-log'></small>
                        </div>
                    </div>
                    <div class='form-row'>
                        <div class='form-group m-auto'>
                            <input class='btn btn-primary mt-4 mb-3' type='submit' name='log' value='Đăng nhập'>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>