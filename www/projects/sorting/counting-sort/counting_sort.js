function counting_sort(unsorted_array) {
    let max = 0;
    for (let i=0; i < unsorted_array.length; i++){
        if (unsorted_array[i] > max)
            max = unsorted_array[i];
    }
    let element_count = [];
    for (let i = 0; i < unsorted_array.length; i++) {
        if (element_count[unsorted_array[i]])
            element_count[unsorted_array[i]]++;
        else
            element_count[unsorted_array[i]] = 1;
    }
    let sorted_array = [];
    for (let i = 0; i < element_count.length; i++) {
        for (let j = 0; j < element_count[i]; j++) {
            sorted_array.push(i);
        }
    }
    return sorted_array;
}

function test_count_sort(){
    let pi = [3, 1, 4, 1];
    let sorted_pi = counting_sort(pi);
    document.getElementById("sortedPI").innerText = "[" + sorted_pi.join(", ") + "]";
}