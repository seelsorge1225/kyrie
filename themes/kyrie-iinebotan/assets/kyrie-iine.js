/* いいねボタン改: 記事ごとにボタン名（data-iinename）を自動で付ける。newiine.js より先に読み込むこと */
(function () {
  var path = decodeURIComponent(location.pathname);
  var name = path.replace(/[^A-Za-z0-9_-]+/g, '_').replace(/^_+|_+$/g, '') || 'top';
  if (/[^\x00-\x7F]/.test(path)) {
    var h = 5381;
    for (var i = 0; i < path.length; i++) { h = ((h << 5) + h + path.charCodeAt(i)) >>> 0; }
    name += '-' + h.toString(36);
  }
  var btns = document.querySelectorAll('.newiine_btn');
  for (var j = 0; j < btns.length; j++) { btns[j].setAttribute('data-iinename', name); }
})();
