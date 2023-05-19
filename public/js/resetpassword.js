const formulario = document.getElementById("formulario");
const inputs = document.querySelectorAll("#formulario input");
const myButton = document.getElementById("btn-disable");
myButton.disabled = true;
myButton.style.opacity = 0.4;
const expresiones = {
    password:
        /^(?=.*[ !@#$%^&*()_+\-=[\]{};':"\\|,.<>/?])(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$/,
};

const campos = {
    password: false,
};

const validarFormulario = (e) => {
    switch (e.target.name) {
        case "password":
            validarCampo(expresiones.password, e.target, "password");
            validarPassword2();

            break;
        case "password_confirmation":
            validarPassword2();
            break;
    }
};

const validarCampo = (expresion, input, campo) => {
    if (expresion.test(input.value)) {
        document
            .getElementById(`grupo__${campo}`)
            .classList.remove("formulario__grupo-incorrecto");
        document
            .getElementById(`grupo__${campo}`)
            .classList.add("formulario__grupo-correcto");
        document
            .querySelector(`#grupo__${campo} i`)
            .classList.add("fa-check-circle");
        document
            .querySelector(`#grupo__${campo} i`)
            .classList.remove("fa-times-circle");
        document
            .querySelector(`#grupo__${campo} .formulario__input-error`)
            .classList.remove("formulario__input-error-activo");
        campos[campo] = true;
        myButton.disabled = false;
        myButton.style.opacity = 1;
        myButton.style.backgroundColor = "#4caf50";
    } else {
        document
            .getElementById(`grupo__${campo}`)
            .classList.add("formulario__grupo-incorrecto");
        document
            .getElementById(`grupo__${campo}`)
            .classList.remove("formulario__grupo-correcto");
        document
            .querySelector(`#grupo__${campo} i`)
            .classList.add("fa-times-circle");
        document
            .querySelector(`#grupo__${campo} i`)
            .classList.remove("fa-check-circle");
        document
            .querySelector(`#grupo__${campo} .formulario__input-error`)
            .classList.add("formulario__input-error-activo");
        campos[campo] = false;
    }
};

const validarPassword2 = () => {
    const inputPassword1 = document.getElementById("password");
    const inputPassword2 = document.getElementById("password_confirmation");

    if (
        inputPassword1.value !== inputPassword2.value ||
        inputPassword2.value === ""
    ) {
        document
            .getElementById(`grupo__password2`)
            .classList.add("formulario__grupo-incorrecto");
        document
            .getElementById(`grupo__password2`)
            .classList.remove("formulario__grupo-correcto");
        document
            .querySelector(`#grupo__password2 i`)
            .classList.add("fa-times-circle");
        document
            .querySelector(`#grupo__password2 i`)
            .classList.remove("fa-check-circle");
        document
            .querySelector(`#grupo__password2 .formulario__input-error`)
            .classList.add("formulario__input-error-activo");
        campos["password"] = false;
    } else {
        document
            .getElementById(`grupo__password2`)
            .classList.remove("formulario__grupo-incorrecto");
        document
            .getElementById(`grupo__password2`)
            .classList.add("formulario__grupo-correcto");
        document
            .querySelector(`#grupo__password2 i`)
            .classList.remove("fa-times-circle");
        document
            .querySelector(`#grupo__password2 i`)
            .classList.add("fa-check-circle");
        document
            .querySelector(`#grupo__password2 .formulario__input-error`)
            .classList.remove("formulario__input-error-activo");
        campos["password"] = true;
    }
};

inputs.forEach((input) => {
    input.addEventListener("keyup", validarFormulario);
    input.addEventListener("blur", validarFormulario);
});

formulario.addEventListener("submit", (e) => {
    e.preventDefault();

    if (campos.password) {
        Object.entries(campos).forEach(([key, val]) => {
            campos[key] = false;
        });
        document
            .getElementById("formulario__mensaje-exito")
            .classList.add("formulario__mensaje-exito-activo");
        setTimeout(() => {
            document
                .getElementById("formulario__mensaje-exito")
                .classList.remove("formulario__mensaje-exito-activo");
        }, 500000);

        document
            .querySelectorAll(".formulario__grupo-correcto")
            .forEach((icono) => {
                icono.classList.remove("formulario__grupo-correcto");
            });
        // utilisar el metodo post  para enviar los datos
        const inputPassword1 = document.getElementById("password");
        const inputPassword2 = document.getElementById("password_confirmation");
        const iduser = document.getElementById("iduser");
        const data = {
            password: inputPassword1.value,
            confirm_password: inputPassword2.value,
            id: iduser.value,
        };

        console.log(data);
        fetch(`/api/reset-password`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.success === false) {
                    console.log(data.msg);
                } else if (data.success === true) {
                    console.log(data.msg);

                    setTimeout(() => {
                        window.location.href = "resetinfo";
                    }, 1000);
                }
            });

        formulario.reset();
    } else {
        document
            .getElementById("formulario__mensaje")
            .classList.add("formulario__mensaje-activo");
    }
});
