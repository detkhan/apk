//
//  RealtimeCell.swift
//  APPFORMANAGEMENT
//
//  Created by Chanakan Jumnongwit on 3/24/2560 BE.
//  Copyright © 2560 REVO. All rights reserved.
//

import UIKit

class RealtimeCell: UITableViewCell {
    @IBOutlet weak var sawmillnameLabel:UILabel!
    @IBOutlet weak var woodincomingLabel:UILabel!
    @IBOutlet weak var price_per_kgLabel:UILabel!
    @IBOutlet weak var price_totalLabel:UILabel!
    @IBOutlet weak var timeLabel:UILabel!
    @IBOutlet weak var forwardImage:UIImageView!
    @IBOutlet weak var circleImage:UIImageView!
    override func awakeFromNib() {
        super.awakeFromNib()
        // Initialization code
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)

        // Configure the view for the selected state
    }

}
