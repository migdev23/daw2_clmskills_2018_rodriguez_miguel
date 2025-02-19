"use strict";
console.log('hola2');
function v() {
    return 1;
}
const v2 = () => {
    if (1 + 1 == 2) {
        throw new Error("error");
    }
    return 1;
};
try {
    const variable = v2();
    console.log(variable);
}
catch (error) {
    console.log('control' + error);
}
console.log('adios');
//# sourceMappingURL=index.js.map