const express = require("express");
const axios = require("axios");
const cheerio = require("cheerio");

const app = express();
const PORT = 5001;

app.use(express.json());
app.use(
    express.urlencoded({
        extended: true,
    })
);

app.all("/", (req, res) => {
    res.json({
        text: "running..",
    });
});

app.all("/types", async (req, res) => {
    const response = await axios.get("https://kursdollar.org/real-time/USD");
    const $ = cheerio.load(response.data);
    let options = $("#container > div:nth-child(1) > div > select").children();
    // console.log(options[0]);
    let types = {};
    for (let i = 0; i < options.length; i++) {
        types[options[i].attribs.value] = options[i].children[0].data;
    }
    res.json(types);
});

app.all("/:to", async (req, res) => {
    const { to } = req.params;
    const response = await axios.get(
        "https://kursdollar.org/real-time/" + to.toUpperCase()
    );
    const $ = cheerio.load(response.data);
    let value = $(
        "#container > div:nth-child(2) > div.col-md-4 > table > tbody > tr:nth-child(3) > td:nth-child(1)"
    ).text();
    value = value.replace(/\./g, "");
    value = value.replace(/\,/g, ".");
    value = Number.parseFloat(value);
    res.json(value);
});

app.listen(PORT, () => {
    console.log("running at http://localhost:" + PORT);
});
