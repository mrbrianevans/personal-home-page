
interface CustomInputProps{

}

// @ts-ignore
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
class Dashboard extends React.Component {
    render() {
        return <Counter/>;
    }
}

ReactDOM.render(<Dashboard buttonTitle={"PressableOpacity"} defaultPressed={true}/>, document.getElementById('dashboard-container'));