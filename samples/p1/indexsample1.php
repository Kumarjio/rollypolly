
<!DOCTYPE html><html class=''>
<head><meta charset='UTF-8'>

<style class="cp-pen-styles">body {
  color: #333;
  font-family: 'Helvetica', arial;
}

.wrap {
  padding: 40px;
  text-align: center;
}

hr {
  clear: both;
  margin-top: 40px;
  margin-bottom: 40px;
  border: 0;
  border-top: 1px solid #aaa;
}

h1 {
  font-size: 30px;
  margin-bottom: 40px;
}

p {
  margin-bottom: 20px;
}

.btn {
  background: #428bca;
  border: #357ebd solid 1px;
  border-radius: 3px;
  color: #fff;
  display: inline-block;
  font-size: 14px;
  padding: 8px 15px;
  text-decoration: none;
  text-align: center;
  min-width: 60px;
  position: relative;
  transition: color .1s ease;
}
.btn:hover {
  background: #357ebd;
}
.btn.btn-big {
  font-size: 18px;
  padding: 15px 20px;
  min-width: 100px;
}

.btn-close {
  color: #aaa;
  font-size: 30px;
  text-decoration: none;
  position: absolute;
  right: 5px;
  top: 0;
}
.btn-close:hover {
  color: #909090;
}

.modal:before {
  content: "";
  /*display: none;*/
  background: transparent;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: -1;
}
.modal:target:before {
  -webkit-transition: -webkit-transform 0.9s ease-out;
  -moz-transition: -moz-transform 0.9s ease-out;
  -o-transition: -o-transform 0.9s ease-out;
  transition: transform 0.9s ease-out;
  z-index: 10;
  background: rgba(0, 0, 0, 0.6);
  /*display: block;*/
}

.modal:target + .modal-dialog {
  -webkit-transform: translate(0, 0);
  -ms-transform: translate(0, 0);
  transform: translate(0, 0);
  top: 20%;
}

.modal-dialog {
  background: #fefefe;
  border: #333 solid 1px;
  border-radius: 5px;
  margin-left: -200px;
  position: fixed;
  left: 50%;
  top: -100%;
  z-index: 11;
  width: 360px;
  -webkit-transform: translate(0, -500%);
  -ms-transform: translate(0, -500%);
  transform: translate(0, -500%);
  -webkit-transition: -webkit-transform 0.3s ease-out;
  -moz-transition: -moz-transform 0.3s ease-out;
  -o-transition: -o-transform 0.3s ease-out;
  transition: transform 0.3s ease-out;
}

.modal-body {
  padding: 20px;
}

.modal-header,
.modal-footer {
  padding: 10px 20px;
}

.modal-header {
  border-bottom: #eee solid 1px;
}
.modal-header h2 {
  font-size: 20px;
}

.modal-footer {
  border-top: #eee solid 1px;
  text-align: right;
}
</style></head><body>

<div class="wrap">

  <h1>Modal - Pure CSS (no Javascript)</h1>
  
  <p>Example of modal in CSS only, here I use the pseudo selector ":target" and no javascript for modal action.</p>
  
  <p>This works in IE9+ and all modern browsers.</p>
  
  <hr />
  
  <a href="#modal-one" class="btn btn-big">Modal!</a>
  <a href="#modal-two" class="btn btn-big">Modal!</a>
  
</div>
 
<!-- Modal -->
<a href="#" class="modal" id="modal-one" aria-hidden="true">
  </a>
  <div class="modal-dialog">
    <div class="modal-header">
      <h2>Modal in CSS?</h2>
      <a href="#" class="btn-close" aria-hidden="true">×</a>
    </div>
    <div class="modal-body">
      <p>One modal example here! :D</p>
    </div>
    <div class="modal-footer">
      <a href="#" class="btn">Nice!</a>
    </div>
  </div>

<!-- /Modal -->
<!-- Modal2 -->
<a href="#" class="modal" id="modal-two" aria-hidden="true">
  </a>
  <div class="modal-dialog">
    <div class="modal-header">
      <h2>Modal in TWO</h2>
      <a href="#" class="btn-close" aria-hidden="true">×</a>
    </div>
    <div class="modal-body">
      <p>Two modal example here! :D</p>
    </div>
    <div class="modal-footer">
      <a href="#" class="btn">Nice!</a>
    </div>
  </div>

<!-- /Modal2 -->
<script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js'></script>
</body></html>