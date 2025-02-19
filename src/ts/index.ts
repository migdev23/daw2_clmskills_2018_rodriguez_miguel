console.log('hola2');

function v ():number {
    return 1;
}

const v2 = ():number =>{

    if(1+1 == 2 ){
        throw new Error("error");
    }

    return 1;
}

try {
    const variable:number = v2();
    console.log(variable)
} catch (error) {
    console.log('control' + error);
}


console.log('adios');