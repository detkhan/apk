//
//  TabNavigationView.swift
//  APPFORMANAGEMENT
//
//  Created by Chanakan Jumnongwit on 1/30/2560 BE.
//  Copyright © 2560 REVO. All rights reserved.
//

import UIKit

struct TagValue{
    static let TITLE_TEXT = "TITLE_TEXT"
    static let LEFT_BUTTON = "LEFT_BUTTON"
    static let IMAGE_NAME = "IMAGE_NAME"
}

enum ShowStatus{
    case BUTTON_HIDDEN , BUTTON_SHOWN
}

class TabNavigationView: NSObject {
    
    //MARK: - HOW TO USE TabNavigationView
    /*
     let newToDo = ["TITLE_TEXT":"CJ"]
     TabNavigationView.addNavigationView(dict: newToDo as [String : AnyObject], viewController: self)
     */
    class func addNavigationView(dict:[String:String],viewController:UIViewController,buttonHidden:Bool) -> Void{
        
        viewController.navigationController?.isNavigationBarHidden = true
        var navigationView = UIView()
        var frame = CGRect()
        if UIDevice.current.userInterfaceIdiom == .phone {
            navigationView = Bundle.main.loadNibNamed("TabNavigationBar", owner:nil, options: nil)?[0] as! UIView
            frame = CGRect(x: 0, y: 0, width:viewController.view.frame.size.width, height:66)
        }else{
            navigationView = Bundle.main.loadNibNamed("TabNavigationBar", owner:nil, options: nil)?[1] as! UIView
            frame = CGRect(x: 0, y: 0, width:viewController.view.frame.size.width, height:86)
        }
        //let navigationView = Bundle.main.loadNibNamed("TabNavigationBar", owner:nil, options: nil)?[0] as! UIView
        //let frame = CGRect(x: 0, y: 0, width:viewController.view.frame.size.width, height:66)
        navigationView.frame = frame
        
        if let titlelbl = navigationView.viewWithTag(1) as? UILabel{
            titlelbl.text = (dict[TagValue.TITLE_TEXT])
        }
        
        if let backImage = navigationView.viewWithTag(2) as? UIImageView{
            backImage.image = UIImage(named:dict[TagValue.IMAGE_NAME]!)
        }
        
        if let leftButton = navigationView.viewWithTag(3) as? UIButton{
            leftButton.addTarget(viewController, action:NSSelectorFromString(dict[TagValue.LEFT_BUTTON]!), for: UIControlEvents.touchUpInside)
            //rightButton.isHidden = buttonHidden
        }
        viewController.view.addSubview(navigationView)
    }
}
