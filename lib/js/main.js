document.addEventListener("DOMContentLoaded", function () {
    var header = document.querySelector('.header');
    var l = document.querySelectorAll('.effect');
    var menu = document.querySelector('.menu-toggle');
    var main = document.querySelector('.main');
    var ct = document.querySelectorAll('.content');
    var body = document.querySelectorAll('.c-body');
    var readmore = document.querySelectorAll('.read-more');
    window.onscroll = function () {
        if (window.pageYOffset > 1) {
            header.classList.add("sticky");
            l.forEach(function (li) {
                li.classList.remove('ef');
            });
            document.querySelector('.img').setAttribute('src', './lib/images/logo.png');
        }
        else {
            header.classList.remove("sticky");
            l.forEach(function (li) {
                li.classList.add('ef');
            });
            document.querySelector('.img').setAttribute('src', './lib/images/cdlncd.png');
        }
    }

    menu.onclick = function () {
        menu.classList.toggle("change");
        document.querySelector('#full-menu').classList.toggle("show");
        main.classList.toggle("blur");
    };

    for (let i = 0; i < body.length && i < readmore.length && i < ct.length; i++) {
        if (body[i].clientHeight > 250) {
            body[i].setAttribute('style', 'height: 250px;');
            readmore[i].innerHTML = 'Đọc thêm';
        }
        readmore[i].onclick = function () {
            if (body[i].clientHeight > 250) {
                window.scrollTo(0, ct[i].offsetTop - 70);
                body[i].setAttribute('style', 'height: 250px;');
                readmore[i].innerHTML = 'Đọc thêm';
            }
            else {
                body[i].removeAttribute('style');
                readmore[i].innerHTML = 'Thu gọn';
            }
        }
    }
})
// document.addEventListener('DOMContentLoaded', function () {
//     function Er(selector_input, selector_error, char_number_min, char_number_max) {
//         let name = document.querySelector(selector_input);
//         if (name.value.length < char_number_min || name.value.length >= char_number_max) {
//             document.querySelector(selector_error).innerHTML = `Vui lòng nhập ít nhất ${char_number_min} ký tự và không quá ${char_number_max} ký tự.`;
//         }
//         else
//             document.querySelector(selector_error).innerHTML = '';
//     };
//     if (document.querySelector('#name') != null) {

//         document.querySelector('#name').onchange = function () {
//             Er('#name', '#err', 8, 50);
//         };

//         document.querySelector('#user-name').onchange = function () {
//             Er('#user-name', '#err1', 6, 20);
//         }

//         document.querySelector('#user-name-log').onchange = function () {
//             Er('#user-name-log', '#err1', 6, 20);
//         }
//         let pass = document.querySelector('#pwd'), rpass = document.querySelector('#rpwd');
//         let passLog = document.querySelector('#pwd-log');
//         rpass.onchange = function () {
//             if (rpass.value != pass.value)
//                 document.querySelector('#err2').innerHTML = 'Vui lòng nhập mật khẩu trùng nhau.';
//             else
//                 document.querySelector('#err2').innerHTML = '';
//         }
//         pass.onchange = function () {
//             let reg = /(?=.*[a-z])(?=.*\d){6,15}/;
//             if (reg.test(pass.value))
//                 document.querySelector('#err3').innerHTML = '';
//             else
//                 document.querySelector('#err3').innerHTML = 'Vui lòng nhập mật khẩu 6 - 15 ký tự bao gồm, chữ in thường và số.';
//         }

//     }
// })

$(document).ready(function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
    $('.notify').on("mouseenter", function () {
        $(this).removeClass('newNotify');
    })
})