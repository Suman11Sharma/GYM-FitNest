<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-md py-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0 text-center flex-grow-1">AboutUs</h2>
                <!-- Back button -->
                <a href="./index.php" class="btn btn-light btn-sm border ms-3" title="Back to Home">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>

            <form>

                <!-- CARD 1 -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">Card 1</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Heading Title</label>
                            <input type="text" class="form-control" name="card1Title" placeholder="Enter heading title">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description Body (List Format)</label>
                            <div id="descriptionList1">
                                <div class="input-group mb-2">
                                    <span class="input-group-text">•</span>
                                    <input type="text" name="card1Description[]" class="form-control" placeholder="Description point">
                                    <button type="button" class="btn btn-danger" onclick="removeDescription(this)">Remove</button>
                                </div>

                            </div>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="addDescription('descriptionList1', 'card1Description[]')">Add More</button>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sub-title (in quotation)</label>
                            <input type="text" class="form-control" name="card1Subtitle" placeholder='"Your quote here"'>
                        </div>
                    </div>
                </div>

                <!-- CARD 2 -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-success text-white">Card 2</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Heading Title</label>
                            <input type="text" class="form-control" name="card2Title" placeholder="Enter heading title">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description Body (List Format)</label>
                            <div id="descriptionList2">
                                <div class="input-group mb-2">
                                    <span class="input-group-text">•</span>
                                    <input type="text" name="card2Description[]" class="form-control" placeholder="Description point">
                                    <button type="button" class="btn btn-danger" onclick="removeDescription(this)">Remove</button>
                                </div>

                            </div>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="addDescription('descriptionList2', 'card2Description[]')">Add More</button>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sub-title (in quotation)</label>
                            <input type="text" class="form-control" name="card2Subtitle" placeholder='"Your quote here"'>
                        </div>
                    </div>
                </div>

                <!-- CARD 3 -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-warning text-dark">Card 3</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Heading Title</label>
                            <input type="text" class="form-control" name="card3Title" placeholder="Enter heading title">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description Body (List Format)</label>
                            <div id="descriptionList3">
                                <div class="input-group mb-2">
                                    <span class="input-group-text">•</span>
                                    <input type="text" name="card3Description[]" class="form-control" placeholder="Description point">
                                    <button type="button" class="btn btn-danger" onclick="removeDescription(this)">Remove</button>
                                </div>

                            </div>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="addDescription('descriptionList3', 'card3Description[]')">Add More</button>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sub-title (in quotation)</label>
                            <input type="text" class="form-control" name="card3Subtitle" placeholder='"Your quote here"'>
                        </div>
                    </div>
                </div>

                <!-- SUBMIT -->
                <div class="text-center">
                    <button type="submit" class="btn btn-our px-5 py-2">Submit</button>
                </div>
            </form>
        </div>

        <script>
            function addDescription(containerId, inputName) {
                const container = document.getElementById(containerId);
                const div = document.createElement("div");
                div.className = "input-group mb-2";
                div.innerHTML = `
    <span class="input-group-text">•</span>
    <input type="text" name="${inputName}" class="form-control" placeholder="Description point">
    <button type="button" class="btn btn-danger" onclick="removeDescription(this)">Remove</button>
  `;
                container.appendChild(div);
            }

            function removeDescription(button) {
                button.parentElement.remove();
            }
        </script>

    </main>
    <?php require("../layouts/footer.php"); ?>