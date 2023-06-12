// SPDX-License-Identifier: MIT
// ELCOFRE V1
pragma solidity ^0.8.18;

import "../SafeERC20.sol";

contract elcofre  {
    address public owner;
    uint256 public balance;
    
    event TransferReceived(address _from, uint _amount);
    event TransferSent(address _from, address _destAddr, uint _amount);
   
    constructor() {
        owner = msg.sender;
    }
    
    receive() payable external {
        balance += msg.value;
        emit TransferReceived(msg.sender, msg.value);
    }    

    function withdraw(IERC20 token, uint amount) public {
        uint erc20balance = token.balanceOf(address(this));
        require(msg.sender == owner && amount <= erc20balance);
            amount = amount * 10000;
            token.transfer(msg.sender, amount);
            emit TransferSent(msg.sender, msg.sender, amount);
    }

    function transferOwnership(address newAddress) public {
        require(msg.sender == owner);
        owner = newAddress;
    }

}
