"use strict";

const userInfo = {
    radio: 0,
    phone: 0,
    budget: 50,
    text: 0,
    rating: 0,
    email: 0,
    checkbox: 0,
};

function getData() {
    function checkRadio() {
        let radio = document.getElementsByName("r1");
        for (let i = 0; i < radio.length; i++) {
            if (radio[i].checked) {
                return i + 1;
                break;
            }
        }
    }

    function checkCheckbox() {
        let chbox = document.querySelectorAll(".checkbox");
        let chboxChecked = [];
        for (let i = 0; i < chbox.length; i++) {
            if (chbox[i].checked) {
                chboxChecked.push(chbox[i].value);
            }
        }
        userInfo.checkbox = chboxChecked;
    }

    function getSelect() {
        let sel = document.querySelector("#mySelect").selectedIndex;
        let options = document.querySelector("#mySelect").options;
        userInfo.phone = options[sel].text;
    }

    function getTextarea() {
        let textareaText = document.querySelector("#textareaText");
        userInfo.text = textareaText.value;
    }

    function getEmail() {
        let emailInput = document.querySelector("#formEmail");
        userInfo.email = emailInput.value;
    }

    userInfo.radio = checkRadio();
    checkCheckbox();
    getSelect();
    getTextarea();
    getEmail();

    let blockObj = document.querySelector("#blockObj");
    Object.keys(userInfo).forEach((key) => {
        blockObj.innerHTML += `${key}:  ${userInfo[key]} <br>`;
    });
}

let range1 = document.querySelector("#range1");
range1.addEventListener("input", function(e) {
    let valRange1 = document.querySelector("#valRange1");
    valRange1.innerHTML = range1.value;
    userInfo.budget = range1.value;

    let widthRange = document.querySelector("#widthRange");
    widthRange.style.width = range1.value * 2 + "px";
});

// RATING STARS start

const ratings = document.querySelectorAll(".rating");

if (ratings.length > 0) {
    initRatings();
}

function initRatings() {
    let ratingActive, ratingValue;
    for (let i = 0; i < ratings.length; i++) {
        const rating = ratings[i];
        initRating(rating);
    }

    function initRating(rating) {
        initRatingVars(rating);
        setRatingActiveWidth();

        if (rating.classList.contains("rating-set")) {
            setRating(rating);
        }
    }

    function initRatingVars(rating) {
        ratingActive = rating.querySelector(".rating-active");
        ratingValue = rating.querySelector(".rating-value");
    }

    function setRatingActiveWidth(i = ratingValue.innerHTML) {
        const ratingActiveWidth = i / 0.05;
        ratingActive.style.width = `${ratingActiveWidth}%`;
    }

    function setRating(rating) {
        const ratingItems = rating.querySelectorAll(".rating-item");

        for (let i = 0; i < ratingItems.length; i++) {
            const ratingItem = ratingItems[i];

            ratingItem.addEventListener("mouseenter", function(e) {
                initRatingVars(rating);
                setRatingActiveWidth(ratingItem.value);
            });

            ratingItem.addEventListener("mouseleave", function(e) {
                setRatingActiveWidth();
            });
            ratingItem.addEventListener("click", function(e) {
                initRatingVars(rating);

                ratingValue.innerHTML = i + 1;
                setRatingActiveWidth();

                userInfo.rating = i + 1;
            });
        }
    }
}

// RATING STARS end