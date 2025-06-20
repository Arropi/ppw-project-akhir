function tambah(){
    const valueBid = document.getElementById('bid')
    let nowValue = valueBid.value
    
    if(!valueBid){
        nowValue = 0
    }
    valueBid.value = parseInt(nowValue) + 10000
}
function kurang(){
    const valueBid = document.getElementById('bid')
    let nowValue = valueBid.value
    if(!nowValue){
        nowValue = 0
    }
    valueBid.value = parseInt(nowValue) - 10000
}