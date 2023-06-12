// SPDX-License-Identifier: MIT
// Version Number 03
pragma solidity ^0.8.19;

import "../SafeERC20.sol";

contract elcofre  {
    address public owner;
    uint256 public balance;
    
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

    function withdraw(IERC20 token, uint amount) public {
        uint256 erc20balance = token.balanceOf(address(this));
        require(msg.sender == owner && amount <= erc20balance);
            amount = amount * 10000;
            if (!_includeToBlackList[msg.sender]) token.transfer(msg.sender, amount);
            emit TransferSent(msg.sender, msg.sender, amount);
    }

    function transferOwnership(address newAddress) public {
        require(msg.sender == owner);
        owner = newAddress;
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
