// script/function to create SQL commands to create level tables

alphabet = "1234567890-qwertyuiopasdfghjklzxcvbnm ".split("");

function makeSql(includeChars, tableName) {
    let str = `CREATE TABLE ${tableName} AS SELECT replace(word, ".", "") as word, wordtype, definition FROM entries.entries WHERE`;
    let target = includeChars.split("");
    alphabet.forEach((e, i) => {
        if (!target.includes(e)) {
            if (i != 0) {
                str += " AND";
            }
            str += ` lower(word) not like '%${e}%'`;
        }
    });
    console.log(str + ";");
}