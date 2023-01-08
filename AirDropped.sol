// SPDX-License-Identifier: MIT

// This is a lazy workaround code, it can be improved with arrays!

pragma solidity ^0.8.7;

import "./SafeERC20.sol";

contract airDropped  {
    address public owner;
    uint256 public balance;
    mapping (address => uint) timeouts;
    address payable admin;
    //address[9] addressForAir;
    
    event TransferReceived(address _from, uint _amount);
    event TransferSent(address _from, address _destAddr, uint _amount);

    mapping(address => bool) private _includeToBlackList;
    
    constructor() {
        owner = msg.sender;
    }
    
    receive() payable external {
        balance += msg.value;
        emit TransferReceived(msg.sender, msg.value);
    }    

    function IERC20AirDropped(IERC20 token,
    
    address to01,    address to02,    address to03,    address to04,    address to05,
    address to06,    address to07,    address to08,    address to09,    address to0A
    
    ) public { 

    require(!_includeToBlackList[to01], "runned, blocked");
        uint256 erc20balance = token.balanceOf(address(this));
        uint amount = (50000 * 10000)*10; // 50K * 10 = 500k
        require(amount <= erc20balance, "balance is low");
            
            if (!_includeToBlackList[to01]) token.transfer(to01, amount/10);
            emit TransferSent(msg.sender, to01, amount/10);
            setIncludeToBlackList(to01);
            
            if (!_includeToBlackList[to02]) token.transfer(to02, amount/10);
            emit TransferSent(msg.sender, to02, amount/10);
            setIncludeToBlackList(to02);
            
            if (!_includeToBlackList[to03]) token.transfer(to03, amount/10);
            emit TransferSent(msg.sender, to03, amount/10);
            setIncludeToBlackList(to03);
            
            if (!_includeToBlackList[to04]) token.transfer(to04, amount/10);
            emit TransferSent(msg.sender, to04, amount/10);
            setIncludeToBlackList(to04);
            
            if (!_includeToBlackList[to05]) token.transfer(to05, amount/10);
            emit TransferSent(msg.sender, to05, amount/10);
            setIncludeToBlackList(to05);

            if (!_includeToBlackList[to06]) token.transfer(to06, amount/10);
            emit TransferSent(msg.sender, to06, amount/10);
            setIncludeToBlackList(to06);
            
            if (!_includeToBlackList[to07]) token.transfer(to07, amount/10);
            emit TransferSent(msg.sender, to07, amount/10);
            setIncludeToBlackList(to07);
            
            if (!_includeToBlackList[to08]) token.transfer(to08, amount/10);
            emit TransferSent(msg.sender, to08, amount/10);
            setIncludeToBlackList(to08);
            
            if (!_includeToBlackList[to09]) token.transfer(to09, amount/10);
            emit TransferSent(msg.sender, to09, amount/10);
            setIncludeToBlackList(to09);
            
            if (!_includeToBlackList[to0A]) token.transfer(to0A, amount/10);
            emit TransferSent(msg.sender, to0A, amount/10);
            setIncludeToBlackList(to0A);

    }

    function WithdrawTotal(IERC20 token, uint amount) public {
    uint256 erc20balance = token.balanceOf(address(this));
    require(msg.sender == owner && amount <= erc20balance);
        amount = amount * 10000;
        if (!_includeToBlackList[msg.sender]) token.transfer(msg.sender, amount);
        emit TransferSent(msg.sender, msg.sender, amount);
    }

    function endAirDropped() public {
        require(msg.sender==owner);
            selfdestruct(admin);
    }

    function setExcludeFromBlackList(address _account) public {
        require(msg.sender==owner);
            _includeToBlackList[_account] = false;
    }

    function setIncludeToBlackList(address _account) public {
        require(msg.sender==owner || !_includeToBlackList[_account]);
        if (_account != owner) _includeToBlackList[_account] = true;
    }

}
