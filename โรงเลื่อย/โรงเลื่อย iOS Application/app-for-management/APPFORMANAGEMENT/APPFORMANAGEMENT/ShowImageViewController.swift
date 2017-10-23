//
//  ShowImageViewController.swift
//  APPFORMANAGEMENT
//
//  Created by Chanakan Jumnongwit on 3/6/2560 BE.
//  Copyright © 2560 REVO. All rights reserved.
//

import UIKit
import SwiftyJSON
import Alamofire
import SDWebImage
struct TransactionDetail {
    let namein : String
    let nameout : String
    let trucknumber : String
    let price : String
    let weight : String
    let datetimein : String
    let datetimeout : String
}

class ShowImageViewController: UIViewController {

    @IBOutlet weak var busImage:UIImageView!
    @IBOutlet weak var scrollview:UIScrollView!
    @IBOutlet weak var pageControl:UIPageControl!
    
    @IBOutlet weak var customerNamelbl:UILabel!
    @IBOutlet weak var customerNameoutlbl:UILabel!
    @IBOutlet weak var trucknumberlbl:UILabel!
    @IBOutlet weak var weightlbl:UILabel!
    @IBOutlet weak var pricelbl:UILabel!
    @IBOutlet weak var datetimeinlbl:UILabel!
    @IBOutlet weak var datetimeoutlbl:UILabel!
    //var a = ["http://aotskyprivilege.com/images/shop/d4784d67a01ec7c654f69945b360e900.jpg","http://aotskyprivilege.com/images/shop/14c5658fb0b6db08bd76e14c6f93bfcd.jpg","http://aotskyprivilege.com/images/shop/d4784d67a01ec7c654f69945b360e900.jpg"]
    var transactionDetailObj:[JSON] = []
    var object:[String] = []
    var pageControlBeingUsed:Bool  = false
    var picpath:String = ""
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view.
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    override func viewWillAppear(_ animated: Bool) {
        setNavBar()
        setTransactionDetail()
    }
    
    func setNavBar(){
        let newToDo = ["TITLE_TEXT":"IMAGE SCREEN",
                       "LEFT_BUTTON":"onClickedToBack",
                       "IMAGE_NAME":"back_icon"]
        TabNavigationView.addNavigationView(dict: newToDo as [String : String], viewController: self , buttonHidden: false)
    }
    
    func setDataAndScrollImage(objectarr:[String]){
        scrollview.delegate = self;
        pageControl.pageIndicatorTintColor = UIColor.lightGray
        pageControl.currentPageIndicatorTintColor = UIColor.black
        scrollview.isPagingEnabled = true
        scrollview.showsVerticalScrollIndicator = false
        scrollview.showsHorizontalScrollIndicator = false
        pageControlBeingUsed = false
        
        for i in 0..<objectarr.count {
            busImage.frame = CGRect(x: scrollview.frame.size.width * CGFloat(i), y: 0, width: scrollview.frame.size.width,height: scrollview.frame.size.height)
            busImage.sd_setImage(with: URL(string: objectarr[i]))
            scrollview.addSubview(busImage)
        }
        scrollview.contentSize = CGSize(width: self.scrollview.frame.size.width * CGFloat(objectarr.count) , height: self.scrollview.frame.size.height-37)
        pageControl.currentPage = 0
        pageControl.numberOfPages = objectarr.count
        object = objectarr
    }
    
    func setTransactionDetail() {
        var image:String = ""
        for transactions in transactionDetailObj {
            customerNamelbl.text = String(format: "ชื่อคนขับขาเข้า : %@",transactions["customername_in"].stringValue)
            customerNameoutlbl.text = String(format: "ชื่อคนขับขาออก : %@",transactions["customername_out"].stringValue)
            trucknumberlbl.text = String(format: "เลขทะเบียนรถ : %@",transactions["truck_number"].stringValue)
            pricelbl.text = String(format: "ราคา : %@ บาท",transactions["price_total"].stringValue)
            datetimeinlbl.text = String(format: "วันที่เข้า : %@",transactions["datetime_in"].stringValue)
            datetimeoutlbl.text = String(format: "วันที่ออก : %@",transactions["datetime_out"].stringValue)
            weightlbl.text = String(format: "น้ำหนักไม้ : %@ กก.",transactions["weight_total"].stringValue)
            image = transactions["picpath"].stringValue
        }
        if image != "" || !image.isEmpty {
            let imagearr = image.components(separatedBy: "|")
            setDataAndScrollImage(objectarr: imagearr)
        }
    }
}

extension ShowImageViewController{
    @IBAction func onClickedToBack(){
        _ = navigationController?.popViewController(animated: true)
    }
}
//MARK : -Actions Chnage PageControl when click on pagecontrol
extension ShowImageViewController{
    @IBAction func onClikcedToChangePageControl(){
        let frame = CGRect.init(x: scrollview.frame.size.width * CGFloat(pageControl.currentPage), y: 0, width: scrollview.frame.size.width, height: scrollview.frame.size.height)
        scrollview.scrollRectToVisible(frame, animated: true)
        pageControlBeingUsed = true
    }
}

//MARK : UIScrollView Delegate
extension ShowImageViewController : UIScrollViewDelegate {
    func scrollViewDidScroll(_ scrollView: UIScrollView) {
        if !pageControlBeingUsed {
            let pageWidth = scrollview.frame.width
            let newPage = floor((scrollView.contentOffset.x - pageWidth / 2) / pageWidth) + 1
            pageControl.currentPage = Int(newPage) % object.count
        }
    }
    
    func scrollViewWillBeginDragging(_ scrollView: UIScrollView) {
        pageControlBeingUsed = false
    }
    
    func scrollViewDidEndDecelerating(_ scrollView: UIScrollView) {
        pageControlBeingUsed = false
    }
}
