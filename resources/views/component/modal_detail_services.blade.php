
<div class="modal-overlay hide">
    <div class="modal-wrapper">
        <div class="close-btn-wrapper">
            <button class="close-modal-btn">
                Close
            </button>
        </div>
        <h1>GeeksforGeeks</h1>
        <div class="modal-content">
            Greetings from GeeksforGeeks
        </div>
    </div>
</div>
<style>
  .modal-overlay {
    background: rgba(0, 0, 0, 0.7);
    width: 100%;
    height: 100%;
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
}

.modal-wrapper {
    width: 300px;
    height: 300px;
    background: ghostwhite;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.open-modal-btn-wrapper {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.close-modal-btn,
.open-modal-btn {
    padding: 8px;
    background: slateblue;
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    font-size: 15px;
    color: ghostwhite;
    font-weight: 5px;
    margin-left: auto;
    margin-top: 10px;
    margin-right: 10px;
    cursor: pointer;
}

.close-btn-wrapper {
    display: flex;
}

.modal-content {
    margin: 20px auto;
    max-width: 210px;
    width: 100%;
}

.hide {
    display: none;
}

h1 {
    text-align: center;
}
</style>


