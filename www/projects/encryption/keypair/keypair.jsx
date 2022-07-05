
class Visualiser extends React.Component{
    constructor() {
        super();
        this.state = {privateKey: "", publicKey: ""};
        let keyPair = generateKeyPair();
        //     .then(keys=> {
        //     console.log(keys);
        //     this.state.publicKey = "keys.publicKey";
        //     this.state.privateKey = "private key set, promise returned";
        // })
        console.log(keyPair);
    }
    render = () => (
        <div>
            <Document content={this.state.privateKey}/>
            <Document content={"this.state.publicKey"}/>
        </div>
    )
}

class Document extends React.Component{
    constructor(props) {
        super(props);
        console.log("Document is being drawn")
    }
    render = () => (
        <div>
            <p>{this.props.content}</p>
        </div>
    )
}

function runVisualiser(){
    console.log("Body has loaded");
    ReactDOM.render(<Visualiser/>, document.getElementById("root"));
    console.log(generateKeyPair());
}
const getStringFromArrayBuffer = (arrayBuffer) => {
    return btoa(String.fromCharCode(...new Uint8Array(arrayBuffer)));
}
const generateKeyPair = async () => {
    let testKeys = await window.crypto.subtle.generateKey(
        {
            name: "RSA-OAEP",
            modulusLength: 256,
            publicExponent: new Uint8Array([1, 0, 1]),
            hash: "SHA-512"
        },
        true,
        ["encrypt", "decrypt"]
    ).then((keys) => {
        window.crypto.subtle.exportKey("pkcs8", keys.privateKey)
            .then((privateKey) => {
                window.crypto.subtle.exportKey("spki", keys.publicKey)
                    .then((publicKey) => {
                        document.write("Public key: " + getStringFromArrayBuffer(publicKey));
                        document.write("Private key: " + getStringFromArrayBuffer(privateKey));
                        return {
                            privateKey: getStringFromArrayBuffer(privateKey),
                            publicKey: getStringFromArrayBuffer(publicKey)
                        };
                    })
            })
    })
    console.log("Test keys; " + testKeys);
}