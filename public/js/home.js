function init(){
    btn1 = document.getElementById('btn-1');

    btn1.addEventListener('click', () => {
        alert(111);
    });
}


window.addEventListener("DOMContentLoaded", init);