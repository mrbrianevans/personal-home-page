
class CustomInput extends  React.Component{
    constructor(props) {
        super(props);
    }

    render(){
        return <input type={"text"}
                      onKeyPress={this.props.onTextChange}
                      placeholder={this.props.placeholder}
        />
    }
}

class Block extends React.Component{
    constructor(props) {
        super(props);
    }
    render(){
        return (
            <div style={{display: "inline-block", width: 50, textAlign: "center"}}>|</div>
        )

    }
}

class ButtonIncrementer extends React.Component{
    constructor(props) {
        super(props);
    }
    render(){
        return <button onClick={this.props.onclick}>{this.props.label}</button>
    }
}

class NewSubjectForm extends React.Component{
    constructor(props) {
        super(props);
    }
    render(){
        return (
            <div>
            <CustomInput placeholder={"Enter subject name"}/>
        </div>
        )
    }
}

class Counter extends React.Component{
    constructor(props) {
        super(props);
        this.state = {count: 0}
    }
    increaseCount = () =>{
        this.setState((prevState)=> ({count: prevState.count+1}))
    }
    decreaseCount = () =>{
        this.setState((prevState)=> ({count: prevState.count-1}))
    }
    render(){
        return (
            <div>
                <ButtonIncrementer label={"-"} onclick={this.decreaseCount}/><Block/>
                <ButtonIncrementer label={"+"} onclick={this.increaseCount}/>
                <div>{Array(this.state.count).fill(0).map((zero, i)=>{return <NewSubjectForm key={i}/>})}</div>
            </div>
        )
    }
}

class Dashboard extends React.Component {
    render() {
        return <Counter/>;
    }
}

ReactDOM.render(<Dashboard buttonTitle={"PressableOpacity"} defaultPressed={true}/>, document.getElementById('dashboard-container'));