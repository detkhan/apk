//
//  RealTimeViewController.swift
//  APPFORMANAGEMENT
//
//  Created by Chanakan Jumnongwit on 1/30/2560 BE.
//  Copyright © 2560 REVO. All rights reserved.
//

import UIKit
import SWRevealViewController
import LocalAuthentication
import SwiftyJSON
import Alamofire
import Charts
import KRProgressHUD
class RealTimeViewController: UIViewController {
    @IBOutlet weak var barChartView: BarChartView!
    @IBOutlet weak var datetimeLabel:UILabel!
    @IBOutlet weak var scrollingView:UIScrollView!
    @IBOutlet weak var switchview:UIView!
    @IBOutlet weak var topview:UIView!
    @IBOutlet weak var bottomview:UIView!
    @IBOutlet weak var sawmillview:UIView!

    @IBOutlet weak var tableview:UITableView!
    
    @IBOutlet weak var woodIncomeSwitch:UISwitch!
    @IBOutlet weak var priceUnitSwitch:UISwitch!
    @IBOutlet weak var tranCountSwitch:UISwitch!
    
    public let refreshControl = UIRefreshControl()
    
    //recieve array from service (global for use with function)
    var shortNameArray:Array<String> = []
    var woodIncomeArray:Array<Double>= []
    var priceUnitArray:Array<Double> = []
    var tranCountArray:Array<Double> = []
    
    var timer = Timer()
    var transactionObj = [Transaction]()
    var buttonarray = [UIButton]()
    override func viewDidLoad() {
        super.viewDidLoad()
        setSlideBar()
        setNavBar()
        // Do any additional setup after loading the view.
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    override func viewWillAppear(_ animated: Bool) {
        setScrolling()
        getCurrentDatetime()
        getRealTimeReport()
        setUIRefreshControl()
    }
    
    override func viewWillDisappear(_ animated: Bool) {
        /*
         clear data in object array when exit on this page
         */
        transactionObj.removeAll()
        shortNameArray.removeAll()
        woodIncomeArray.removeAll()
        priceUnitArray.removeAll()
        tranCountArray.removeAll()
        buttonarray.removeAll()
        /*
         set all switch to default
         */
        woodIncomeSwitch.setOn(true, animated: false)
        priceUnitSwitch.setOn(true, animated: false)
        tranCountSwitch.setOn(true, animated: false)
    }
    
    func setNavBar(){
        let newToDo = ["TITLE_TEXT":"รายงานปัจจุบัน",
                       "LEFT_BUTTON":"toggleMenu",
                       "IMAGE_NAME":"hamburger_icon"]
        TabNavigationView.addNavigationView(dict: newToDo as [String : String], viewController: self , buttonHidden: true)
    }
        
    func setSlideBar(){
        if (self.revealViewController() != nil) {
            self.view.addGestureRecognizer(self.revealViewController().tapGestureRecognizer())
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
    }
    
    func toggleMenu(){
        revealViewController().revealToggle(animated: true)
    }
    
    func getRealTimeReport(){
        showHUD()
        let parameter = ["email" : SetttingController.shareInstance.getEmail()]
        DispatchQueue.main.async {
            APKController.shareInstance.realtimedelegate = self;
        }
        APKController.shareInstance.sawmillPostRealTimeReport(parameter: parameter, Key: ServiceKey.SAWMILL_POST_REALTIME_TRANSACTION.rawValue)
    }
    
    func setScrolling(){
        scrollingView.showsVerticalScrollIndicator = false
        scrollingView.showsHorizontalScrollIndicator = false
        scrollingView.addSubview(barChartView)
        scrollingView.addSubview(switchview)
        scrollingView.addSubview(topview)
        scrollingView.contentSize = CGSize(width: self.switchview.frame.size.width , height: (barChartView.frame.size.height) + (switchview.frame.size.height) + (topview.frame.size.height))
        
    }
    
    func setUIRefreshControl(){
        refreshControl.addTarget(self, action: #selector(pulltorefresh), for: UIControlEvents.valueChanged)
        scrollingView.addSubview(refreshControl)
    }
    
    func pulltorefresh(){
        refreshControl.beginRefreshing()
        DispatchQueue.main.async {
            self.getRealTimeReport()
        }
    }
    
    func getCurrentDatetime(){
        let date = Date()
        let formatter = DateFormatter()
        formatter.dateFormat = "dd/MM/YYYY"
        let currentDate = formatter.string(from: date)
        let hours = NSCalendar.current.component(.hour, from: date)
        let miniutes = NSCalendar.current.component(.minute, from: date)
        let seconds = NSCalendar.current.component(.second, from: date)
        if miniutes < 10 {
            datetimeLabel.text = String(format:"วันที่ %@ เวลา %d:0%d:%d นาที",currentDate,hours,miniutes,seconds)
        }else{
            datetimeLabel.text = String(format:"วันที่ %@ เวลา %d:%d:%d นาที",currentDate,hours,miniutes,seconds)
        }
        timer = Timer.scheduledTimer(timeInterval: 1.0, target: self, selector: #selector(timeAction), userInfo: nil, repeats: true)
    }
    
    func timeAction(){
        let date = Date()
        let formatter = DateFormatter()
        formatter.dateFormat = "dd/MM/YYYY"
        let currentDate = formatter.string(from: date)
        let hours = NSCalendar.current.component(.hour, from: date)
        let miniutes = NSCalendar.current.component(.minute, from: date)
        var seconds = NSCalendar.current.component(.second, from: date)
        seconds += 1
        if miniutes < 10 {
            datetimeLabel.text = String(format:"วันที่ %@ เวลา %d:0%d:%d นาที",currentDate,hours,miniutes,seconds)
        }else{
            datetimeLabel.text = String(format:"วันที่ %@ เวลา %d:%d:%d นาที",currentDate,hours,miniutes,seconds)
        }
    }
}

extension RealTimeViewController {
    func showHUD(){
        KRProgressHUD.show(progressHUDStyle: .white, message: "Loading...")
    }
    func hideHUD(){
        DispatchQueue.main.async {
            KRProgressHUD.dismiss()
        }
    }
}

//MARK : - Create Charts
extension RealTimeViewController {
    
    func chart(shortName:Array<String>,woodIncome:Array<Double>,priceUnit:Array<Double>,transactionCount:Array<Double>){
        //barChartView.delegate = self
        barChartView.noDataText = "You need to provide data for the chart."
        barChartView.chartDescription?.text = "" //set description for barChartView
        
        let xaxis = barChartView.xAxis
        //xaxis.valueFormatter = axisFormatDelegate
        xaxis.drawGridLinesEnabled = true
        xaxis.labelPosition = .bottom
        xaxis.centerAxisLabelsEnabled = true
        xaxis.valueFormatter = IndexAxisValueFormatter(values:shortName)
        xaxis.granularity = 1
        
        setChart(shortname: shortName ,weightTotal: woodIncome , priceUnit: priceUnit , tranCount: transactionCount)
    }
    
    func setChart(shortname:Array<String>,weightTotal:Array<Double>,priceUnit:Array<Double>,tranCount:Array<Double>) {
        barChartView.noDataText = "You need to provide data for the chart."
     
        var dataEntriesWoodIncoming: [BarChartDataEntry] = []
        var dataEntriesPricePerUnit: [BarChartDataEntry] = []
        var dataEntriedTransactionCount: [BarChartDataEntry] = []
        
        for i in 0..<shortname.count {
            
            let dataEntryWoodIncoming = BarChartDataEntry(x: Double(i) , y: weightTotal[i])
            dataEntriesWoodIncoming.append(dataEntryWoodIncoming)
                
            let dataEntryPricePerUnit = BarChartDataEntry(x: Double(i) , y: priceUnit[i])
            dataEntriesPricePerUnit.append(dataEntryPricePerUnit)
            
            let dataEntryTransactionCount = BarChartDataEntry(x: Double(i) , y: tranCount[i])
            dataEntriedTransactionCount.append(dataEntryTransactionCount)

        }
        //set description label to tell about barchart
        let chartDataSetWoodIncoming = BarChartDataSet(values: dataEntriesWoodIncoming, label: "Wood Incoming")
        let chartDataSetPricePerUnit = BarChartDataSet(values: dataEntriesPricePerUnit, label: "Price Per Unit")
        let chartDataSetTransactionCount = BarChartDataSet(values: dataEntriedTransactionCount, label: "Transaction Count")
        //group data for make groupbarChart
        let dataSets: [BarChartDataSet] = [chartDataSetWoodIncoming,chartDataSetPricePerUnit,chartDataSetTransactionCount]
        /*
         set color to barchart
         */
        chartDataSetWoodIncoming.colors = [UIColor(red: 102/255, green: 201/255, blue: 111/255, alpha: 1)]
        chartDataSetPricePerUnit.colors = [UIColor(red: 250/255, green: 103/255, blue: 48/255, alpha: 1)]
        chartDataSetTransactionCount.colors = [UIColor(red: 102/255, green: 102/255, blue: 102/255, alpha: 1)]
        
        let chartData = BarChartData(dataSets: dataSets)
        
        let groupSpace = 0.15
        let barSpace = 0.015
        let barWidth = 0.27
        // (0.3 + 0.05) * 2 + 0.3 = 1.00 -> interval per "group" for 2 Group
        // (0.15 + 0.015) * 3 + 0.27 = 1.05 -> for 3 group
        let groupCount = shortname.count
        let startYear = 0
        
        chartData.barWidth = barWidth;
        barChartView.xAxis.axisMinimum = Double(startYear)
        let groupAxis = chartData.groupWidth(groupSpace: groupSpace, barSpace: barSpace)
        barChartView.xAxis.axisMaximum = Double(startYear) + groupAxis * Double(groupCount)
        //print("Groupspace: \(groupAxis)")
        chartData.groupBars(fromX: Double(startYear), groupSpace: groupSpace, barSpace: barSpace)
        //chartData.groupWidth(groupSpace: groupSpace, barSpace: barSpace)
        barChartView.notifyDataSetChanged()
        barChartView.data = chartData
        //background color
        barChartView.backgroundColor = UIColor(red: 240/255, green: 239/255, blue: 245/255, alpha: 1)
        //disable zoom when doubletap and pinch
        barChartView.doubleTapToZoomEnabled = false
        barChartView.pinchZoomEnabled = false
        barChartView.data?.highlightEnabled = false
        //chart animation
        barChartView.animate(xAxisDuration: 1.5, yAxisDuration: 1.5, easingOption: .linear)
    }
}
//MARK : - Actions (when slide on UISwitch)
extension RealTimeViewController {
    @IBAction func onoffSwitch (_ sender: UISwitch){
        let count = checkArray(shortname: self.shortNameArray)
        if count.count > 0 {
            if woodIncomeSwitch.isOn && priceUnitSwitch.isOn && tranCountSwitch.isOn {
                DispatchQueue.main.async {
                    self.chart(shortName: self.shortNameArray, woodIncome: self.woodIncomeArray, priceUnit: self.priceUnitArray, transactionCount: self.tranCountArray)
                }
            }else if woodIncomeSwitch.isOn && priceUnitSwitch.isOn{ //only transaction close
                DispatchQueue.main.async {
                    self.chart(shortName: self.shortNameArray, woodIncome: self.woodIncomeArray, priceUnit: self.priceUnitArray, transactionCount: count)
                }
            }else if woodIncomeSwitch.isOn && tranCountSwitch.isOn{ //only priceperunit close
                DispatchQueue.main.async {
                    self.chart(shortName: self.shortNameArray, woodIncome: self.woodIncomeArray, priceUnit: count, transactionCount: self.tranCountArray)
                }
            }else if priceUnitSwitch.isOn && tranCountSwitch.isOn{ //only wood_income close
                DispatchQueue.main.async {
                    self.chart(shortName: self.shortNameArray, woodIncome: count, priceUnit: self.priceUnitArray, transactionCount: self.tranCountArray)
                }
            }else if woodIncomeSwitch.isOn{ //show only wood_income
                DispatchQueue.main.async {
                    self.chart(shortName: self.shortNameArray, woodIncome: self.woodIncomeArray, priceUnit: count, transactionCount: count)
                }
            }else if priceUnitSwitch.isOn { //show only price per unit
                DispatchQueue.main.async {
                    self.chart(shortName: self.shortNameArray, woodIncome: count, priceUnit: self.priceUnitArray, transactionCount: count)
                }
            }else if tranCountSwitch.isOn { //show only transaction  count
                DispatchQueue.main.async {
                    self.chart(shortName: self.shortNameArray, woodIncome: count, priceUnit: count, transactionCount: self.tranCountArray)
                }
            }else{
                //close all switch
                DispatchQueue.main.async {
                    self.chart(shortName: self.shortNameArray, woodIncome: count, priceUnit: count, transactionCount: count)
                }
            }
        }
    }
    func checkArray(shortname:Array<String>) -> Array<Double>{
        var data:Array<Double> = []
        if shortname.count == 1 {
            data = [0]
        }else if shortname.count == 2 {
            data = [0,0]
        }else if shortname.count == 3 {
            data = [0,0,0,]
        }else if shortname.count == 4 {
            data = [0,0,0,0]
        }
        return data
    }
}
// msdLabel.textColor = UIColor.init(colorLiteralRed: 0, green: 122/255, blue: 255/255, alpha: 1)
//MARK : - for loop to create button
extension RealTimeViewController {
    func createLabelAndButton(parameter:[Realtime]){
        buttonarray.removeAll()
        if parameter.count > 1 {
            bottomview.isHidden = false
            //get transaction from backend when first run
            let param = parameter[0]
            transaction(sawId: param.sawId)
            //create button and add action
            var buttonX:CGFloat = 0
            var viewX:CGFloat = 0
            var button = UIButton()
            for param in parameter {
                if UIDevice.current.userInterfaceIdiom == .phone {
                    button = UIButton.init(frame: CGRect.init(x: buttonX, y: 0, width: (bottomview.frame.size.width / CGFloat(parameter.count) ), height: 50))
                }else{
                    button = UIButton.init(frame: CGRect.init(x: buttonX, y: 0, width: (bottomview.frame.size.width / CGFloat(parameter.count) ), height: 70))
                }
                //let button = UIButton.init(frame: CGRect.init(x: buttonX, y: 0, width: (bottomview.frame.size.width / CGFloat(parameter.count) ), height: 50))
                let view = UIView.init(frame: CGRect.init(x: viewX, y: 5, width: 1, height: bottomview.frame.size.height-10))
                buttonX += bottomview.frame.size.width / CGFloat(parameter.count)
                viewX += bottomview.frame.size.width / CGFloat(parameter.count)
                view.backgroundColor = UIColor.lightGray
                button.setTitleColor(UIColor.lightGray, for: UIControlState.normal)
                button.setTitle(param.shortname, for: UIControlState.normal)
                button.tag = Int(param.sawId)!
                button.addTarget(self, action: #selector(onClickedChangeSawmill(sender:)), for: UIControlEvents.touchUpInside)
                buttonarray.append(button)
                sawmillview.addSubview(view)
                sawmillview.addSubview(button)
            }
        }else{
            //hide bottom view when have one sawmill
            bottomview.isHidden = true
            let param = parameter[0]
            //get transaction from backend when first run
            transaction(sawId: param.sawId)
        }
    }
    
    func onClickedChangeSawmill(sender:UIButton){
        transaction(sawId: String(format: "%d",sender.tag))
        //change button title in buttonarray
        for bt in buttonarray {
            if bt.tag == sender.tag {
                bt.setTitleColor(UIColor.init(colorLiteralRed: 0, green: 122/255, blue: 255/255, alpha: 1), for: .normal)
            }else{
                bt.setTitleColor(UIColor.lightGray, for: .normal)
            }
        }
    }
    
    func transaction(sawId:String){
        showHUD()
        transactionObj.removeAll()
        let parameter = ["sawId" : sawId]
        APKController.shareInstance.realtimedelegate = self;
        APKController.shareInstance.sawmillRealTimeTransaction(parameter: parameter, Key: ServiceKey.SAWMILL_REALTIME_TRANSACTION.rawValue)
    }
}

//MARK : - UITableviewControllerDelegate , UITableViewDataSource
extension RealTimeViewController : UITableViewDelegate , UITableViewDataSource{

    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        if transactionObj.count != 0 {
            return transactionObj.count;
        }
        return 0;
    }
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        let transaction = transactionObj[indexPath.row]
        let param = ["tranId" : transaction.transactionId]
        showHUD()
        APKController.shareInstance.realtimedelegate = self;
        APKController.shareInstance.sawmillRealTimeTransactionDetail(parameter: param, Key: ServiceKey.SAWMILL_REALTIME_TRANSACTION_DETAIL.rawValue)
    }

    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let cell = tableView.dequeueReusableCell(withIdentifier: "RealtimeCell", for: indexPath) as! RealtimeCell
        let transaction = transactionObj[indexPath.row]
        cell.sawmillnameLabel.text = String(format: "ไม้เข้า %@",transaction.fullname)
        cell.price_totalLabel.text = String(format: "ราคาสุทธิ : %@ บาท",transaction.price)
        cell.price_per_kgLabel.text = String(format: "ราคาต่อหน่วย : %@ บาท",transaction.productUnit)
        cell.woodincomingLabel.text = String(format: "ปริมาณไม้เข้า : %@ กรัม",transaction.weight)
        cell.timeLabel.text = String(format: "%@ น.",transaction.time)
        return cell;
    }
}
//MARK : struct model for json data
struct Transaction {
    let fullname : String
    let price : String
    let productUnit : String
    let transactionId : String
    let weight : String
    let time:String
}

struct Realtime {
    let shortname : String
    let priceUnit : String
    let transactionCount : String
    let weightTotal : String
    let sawId : String
}

//MARK : - RealTimeTransactionDelegate
extension RealTimeViewController : RealTimeTransactionDelegate{
    func realtimetransactionRes(response: DataResponse<Any>) {
        hideHUD()
        if let value = response.result.value{
            let json = JSON.init(value)
            let status:NSString = json[0][0]["data"]["status"].stringValue as NSString
            if status.isEqual(to: "true"){
                let serialQueue = DispatchQueue(label: "jsondownload")
                serialQueue.sync {
                    let transactionArray = json[1].arrayValue
                    for transactions in transactionArray {
                        let fullname = transactions["fullname"].stringValue
                        let price = transactions["price_total"].stringValue
                        let productUnit = transactions["product_price_unit"].stringValue
                        let transactionId = transactions["transactionId"].stringValue
                        let weightin = transactions["weight_in"].stringValue
                        let time = transactions["time"].stringValue
                        let transaction = Transaction(fullname:fullname,price:price,productUnit:productUnit,transactionId:transactionId,weight:weightin,time:time)
                        transactionObj.append(transaction)
                    }
                }
                DispatchQueue.main.async {
                    self.tableview.reloadData()
                }
            }else{
                SetttingController.shareInstance.alertcontroller(viewcontroller: self, title: "Information", message: json[0]["data"]["message"].stringValue, titlesubmit: "OK")
                transactionObj.removeAll()
                tableview.reloadData()
            }
        }
    }
    
    func realtimetransactionDetailRes(response: DataResponse<Any>) {
        hideHUD()
        if let value = response.result.value{
            let json = JSON.init(value)
            let status:NSString = json[0][0]["data"]["status"].stringValue as NSString
            if status.isEqual(to: "true"){
                let ShowImageVC = self.storyboard?.instantiateViewController(withIdentifier: "ShowImageViewController") as! ShowImageViewController
                ShowImageVC.transactionDetailObj = json[1].arrayValue
                self.navigationController?.pushViewController(ShowImageVC, animated: true)
            }else{
                SetttingController.shareInstance.alertcontroller(viewcontroller: self, title: "Information", message: json[0]["data"]["message"].stringValue, titlesubmit: "OK")
            }
        }
    }
    
    func getRealtimeTransactionRes(response: DataResponse<Any>) {
        hideHUD()
        refreshControl.endRefreshing()
        transactionObj.removeAll()
        shortNameArray.removeAll()
        woodIncomeArray.removeAll()
        priceUnitArray.removeAll()
        tranCountArray.removeAll()
        var parameters = [Realtime]()
        if let value = response.result.value{
            let json = JSON.init(value)
            let status:NSString = json[0][0]["data"]["status"].stringValue as NSString
            if status.isEqual(to: "true"){
                let serialQueue = DispatchQueue(label: "jsondownload")
                serialQueue.sync {
                    let transactionArray = json[1].arrayValue
                    for transactions in transactionArray {
                        let name = transactions["name"].stringValue
                        let priceUnit = transactions["price_total_per_kg"].stringValue
                        let transactionCount = transactions["transaction_count"].stringValue
                        let weightTotal = transactions["weight_total"].stringValue
                        let sawid = transactions["sawId"].stringValue
                        let transaction = Realtime(shortname:name,priceUnit:priceUnit,transactionCount:transactionCount,weightTotal:weightTotal,sawId:sawid)
                        parameters.append(transaction)
                    }
                }
                for param in parameters {
                    shortNameArray.append(param.shortname)
                    woodIncomeArray.append(Double(param.weightTotal)!)
                    priceUnitArray.append(Double(param.priceUnit)!)
                    tranCountArray.append(Double(param.transactionCount)!)
                }
                DispatchQueue.main.async {
                    self.chart(shortName: self.shortNameArray, woodIncome: self.woodIncomeArray, priceUnit: self.priceUnitArray, transactionCount: self.tranCountArray)
                    self.createLabelAndButton(parameter: parameters)
                }
            }else{
                
            }
        }
    }
}
